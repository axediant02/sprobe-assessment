'use client';
import React from 'react';

interface Book {
	id: number;
	title: string;
	description: string;
	published_at?: string;
	authors?: Array<{ name: string }>;
}

interface BorrowModalProps {
	book: Book | null;
	isOpen: boolean;
	onClose: () => void;
	onConfirm: () => void;
	loanDuration: number;
	onDurationChange: (duration: number) => void;
	isBorrowing: boolean;
}

export default function BorrowModal({
	book,
	isOpen,
	onClose,
	onConfirm,
	loanDuration,
	onDurationChange,
	isBorrowing
}: BorrowModalProps) {
	if (!isOpen || !book) return null;

	return (
		<div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
			<div className="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
				<div className="mt-3">
					<h3 className="text-lg font-medium text-gray-900 mb-4">Borrow Book</h3>
					<div className="mb-4">
						<h4 className="font-semibold text-gray-900">{book.title}</h4>
						{book.authors && book.authors.length > 0 && (
							<p className="text-sm text-gray-600">By {book.authors.map(author => author.name).join(', ')}</p>
						)}
					</div>
					
					<div className="mb-4">
						<label className="block text-sm font-medium text-gray-700 mb-2">
							Loan Duration (days)
						</label>
						<select
							value={loanDuration}
							onChange={(e) => onDurationChange(Number(e.target.value))}
							className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
						>
							<option value={7}>7 days</option>
							<option value={14}>14 days</option>
							<option value={21}>21 days</option>
							<option value={30}>30 days</option>
						</select>
					</div>

					<div className="mb-4 text-sm text-gray-600">
						<p>Due date: {new Date(Date.now() + loanDuration * 24 * 60 * 60 * 1000).toLocaleDateString()}</p>
					</div>

					<div className="flex space-x-3">
						<button
							onClick={onClose}
							className="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
						>
							Cancel
						</button>
						<button
							onClick={onConfirm}
							disabled={isBorrowing}
							className="flex-1 bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
						>
							{isBorrowing ? 'Borrowing...' : 'Confirm Borrow'}
						</button>
					</div>
				</div>
			</div>
		</div>
	);
}