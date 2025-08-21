'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import Header from '../../components/dashboard/Header';
import Navigation from '../../components/dashboard/Navigation';
import OverviewTab from '../../components/dashboard/OverviewTab';
import LoansTab from '../../components/dashboard/LoansTab';
import ProfileTab from '../../components/dashboard/ProfileTab';
import BorrowModal from '../../components/dashboard/BorrowModal';
import MyBooksTab from '../../components/dashboard/MyBooksTab';
import AddBookModal from '../../components/dashboard/AddBookModal';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import api from '../../lib/api';

interface Book {
  id: number;
  title: string;
  description: string;
  published_at?: string;
  authors?: Array<{ name: string }>;
}

interface Loan {
  id: number;
  book: Book;
  loan_date: string;
  return_date: string;
  status: 'ongoing' | 'completed';
}

export default function DashboardPage() {
  const [user, setUser] = useState<any>(null);
  const [activeTab, setActiveTab] = useState('overview');
  const [books, setBooks] = useState<Book[]>([]);
  const [loans, setLoans] = useState<Loan[]>([]);
  const [loading, setLoading] = useState(false);
  const [borrowing, setBorrowing] = useState<number | null>(null);
  const [showBorrowModal, setShowBorrowModal] = useState(false);
  const [selectedBook, setSelectedBook] = useState<Book | null>(null);
  const [loanDuration, setLoanDuration] = useState(14);
  const [showAddBookModal, setShowAddBookModal] = useState(false);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem('token');
    const userData = localStorage.getItem('user');

    if (!token || !userData) {
      router.push('/login');
      return;
    }

    setUser(JSON.parse(userData));
  }, [router]);

  useEffect(() => {
    if (user) {
      if (activeTab === 'overview') {
        fetchBooks();
      } else if (activeTab === 'loans') {
        fetchLoans();
      }
    }
  }, [user, activeTab]);

  const fetchBooks = async () => {
    try {
      setLoading(true);
      const { data } = await api.get('/books');
      setBooks(data.data || data);
    } catch (error) {
      console.error('Error fetching books:', error);
    } finally {
      setLoading(false);
    }
  };

  const fetchLoans = async () => {
    try {
      const { data } = await api.get('/loans');
      setLoans(data.data || data);
    } catch (error) {
      console.error('Error fetching loans:', error);
    }
  };

  const openBorrowModal = (book: Book) => {
    setSelectedBook(book);
    setShowBorrowModal(true);
  };

  const closeBorrowModal = () => {
    setShowBorrowModal(false);
    setSelectedBook(null);
    setLoanDuration(14);
  };

  const handleBorrowBook = async () => {
    if (!selectedBook) return;

    try {
      setBorrowing(selectedBook.id);
      await api.post('/loans', {
        book_id: selectedBook.id,
        loan_date: new Date().toISOString().split('T')[0],
        return_date: new Date(Date.now() + loanDuration * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
      });
      toast.success('Book borrowed successfully!');
      closeBorrowModal();
      fetchBooks();
      if (activeTab === 'loans') {
        fetchLoans();
      }
    } catch (error) {
      console.error('Error borrowing book:', error);
      toast.error('Failed to borrow book. Please try again.');
    } finally {
      setBorrowing(null);
    }
  };

  const handleReturnBook = async (loanId: number) => {
    try {
      await api.put(`/loans/${loanId}`, {
        status: 'completed',
        return_date: new Date().toISOString().split('T')[0],
      });
      toast.success('Book returned successfully!');
      fetchLoans();
    } catch (error) {
      console.error('Error returning book:', error);
      toast.error('Failed to return book. Please try again.');
    }
  };

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    router.push('/login');
  };

  const openAddBookModal = () => setShowAddBookModal(true);
  const closeAddBookModal = () => setShowAddBookModal(false);

  const handleCreateBook = async (payload: { title: string; description?: string }) => {
    try {
      await api.post('/books', {
        title: payload.title,
        description: payload.description,
        published_at: new Date().toISOString().split('T')[0],
      });
      toast.success('Book created!');
      closeAddBookModal();
      fetchBooks();
      setActiveTab('books');
    } catch (err: any) {
      toast.error(err?.response?.data?.message || 'Failed to create book');
    }
  };

  if (!user) {
    return (
      <div className="min-h-screen bg-teal-600 flex items-center justify-center">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-white"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <ToastContainer position="top-center" theme="colored" autoClose={3000} />
      <Header userName={user.name} onLogout={handleLogout} />
      <Navigation activeTab={activeTab} onTabChange={setActiveTab} />

      <main className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {activeTab === 'overview' && (
          <OverviewTab
            books={books}
            loading={loading}
            onBorrow={openBorrowModal}
            borrowingId={borrowing}
          />
        )}

        {activeTab === 'books' && (
          <MyBooksTab books={books} onAddClick={openAddBookModal} />
        )}

        {activeTab === 'loans' && (
          <LoansTab loans={loans} onReturn={handleReturnBook} />
        )}

        {activeTab === 'profile' && (
          <ProfileTab userName={user.name} userEmail={user.email} />
        )}
      </main>

      <BorrowModal
        book={selectedBook}
        isOpen={showBorrowModal}
        onClose={closeBorrowModal}
        onConfirm={handleBorrowBook}
        loanDuration={loanDuration}
        onDurationChange={setLoanDuration}
        isBorrowing={borrowing === selectedBook?.id}
      />

      <AddBookModal
        isOpen={showAddBookModal}
        onClose={closeAddBookModal}
        onConfirm={handleCreateBook}
      />
    </div>
  );
}