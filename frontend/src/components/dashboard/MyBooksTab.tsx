'use client';
import React from 'react';

interface Book {
  id: number;
  title: string;
  description?: string;
  published_at?: string;
  authors?: Array<{ name: string }>;
}

interface MyBooksTabProps {
  books: Book[];
  onAddClick: () => void;
}

export default function MyBooksTab({ books, onAddClick }: MyBooksTabProps) {
  return (
    <div className="px-4 py-6 sm:px-0">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg leading-6 font-medium text-gray-900">My Books</h3>
        <button
          onClick={onAddClick}
          className="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
        >
          <span className="mr-2">+</span> Add Book
        </button>
      </div>

      {books.length === 0 ? (
        <div className="text-center py-10 text-gray-500">No books yet. Click “Add Book” to create one.</div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {books.map((b) => (
            <div key={b.id} className="bg-white rounded-lg shadow p-4">
              <h4 className="font-semibold text-gray-900">{b.title}</h4>
              {b.authors && b.authors.length > 0 && (
                <p className="text-sm text-gray-600 mt-1">By {b.authors.map(a => a.name).join(', ')}</p>
              )}
              {b.published_at && (
                <p className="text-xs text-gray-400 mt-1">Published: {new Date(b.published_at).toLocaleDateString()}</p>
              )}
              {b.description && <p className="text-sm text-gray-600 mt-2 line-clamp-2">{b.description}</p>}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}