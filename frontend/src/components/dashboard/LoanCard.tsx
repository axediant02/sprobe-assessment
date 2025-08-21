'use client';
import React from 'react';

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

interface LoanCardProps {
	loan: Loan;
	onReturn: (loanId: number) => void;
}

export default function LoanCard({ loan, onReturn }: LoanCardProps) {
	return (
		<div className="border rounded-lg p-4">
			<div className="flex justify-between items-center">
				<div>
					<h4 className="font-semibold text-gray-900">{loan.book.title}</h4>
					<p className="text-sm text-gray-600">
						Borrowed: {new Date(loan.loan_date).toLocaleDateString()}
					</p>
					<p className="text-sm text-gray-600">
						Due: {new Date(loan.return_date).toLocaleDateString()}
					</p>
					<span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
						loan.status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'
					}`}>
						{loan.status === 'ongoing' ? 'Active' : 'Completed'}
					</span>
				</div>
				{loan.status === 'ongoing' && (
					<button 
						onClick={() => onReturn(loan.id)}
						className="bg-red-500 text-white px-4 py-2 rounded-md text-sm hover:bg-red-600 transition-colors"
					>
						Return Book
					</button>
				)}
			</div>
		</div>
	);
}