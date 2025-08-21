'use client';
import React from 'react';

import LoanCard from '../../components/dashboard/LoanCard';

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

interface LoansTabProps {
  loans: Loan[];
  onReturn: (loanId: number) => void;
}

export default function LoansTab({ loans, onReturn }: LoansTabProps) {
  return (
    <div className="px-4 py-6 sm:px-0">
      <div className="bg-white shadow rounded-lg">
        <div className="px-4 py-5 sm:p-6">
          <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">My Current Loans</h3>
          {loans.length > 0 ? (
            <div className="space-y-4">
              {loans.map((loan) => (
                <LoanCard key={loan.id} loan={loan} onReturn={onReturn} />
              ))}
            </div>
          ) : (
            <div className="text-center py-8 text-gray-500">
              <p>You don't have any active loans.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}