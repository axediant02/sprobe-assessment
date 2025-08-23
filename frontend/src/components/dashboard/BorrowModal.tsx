'use client';
import React, { useEffect } from 'react';
import { X, Calendar, Clock, BookOpen, User, AlertCircle } from 'lucide-react';

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
	// Close modal on escape key
	useEffect(() => {
		const handleEscape = (e: KeyboardEvent) => {
			if (e.key === 'Escape' && isOpen) {
				onClose();
			}
		};

		if (isOpen) {
			document.addEventListener('keydown', handleEscape);
			document.body.style.overflow = 'hidden'; // Prevent background scroll
		}

		return () => {
			document.removeEventListener('keydown', handleEscape);
			document.body.style.overflow = 'unset';
		};
	}, [isOpen, onClose]);

	// Calculate due date
	const dueDate = new Date(Date.now() + loanDuration * 24 * 60 * 60 * 1000);
	const isOverdue = dueDate < new Date();

	if (!isOpen || !book) return null;

	return (
		<div className="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
			{/* Modal Container */}
			<div className="relative w-full max-w-md bg-white rounded-xl shadow-2xl transform transition-all duration-300 ease-out">
				{/* Header */}
				<div className="flex items-center justify-between p-6 border-b border-gray-100">
					<div className="flex items-center space-x-3">
						<div className="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
							<BookOpen className="w-5 h-5 text-teal-600" />
						</div>
						<div>
							<h3 className="text-lg font-semibold text-gray-900">Borrow Book</h3>
							<p className="text-sm text-gray-500">Confirm your loan details</p>
						</div>
					</div>
					<button
						onClick={onClose}
						className="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors"
						aria-label="Close modal"
					>
						<X className="w-5 h-5 text-gray-400" />
					</button>
				</div>

				{/* Content */}
				<div className="p-6">
					{/* Book Information */}
					<div className="bg-gray-50 rounded-lg p-4 mb-6">
						<div className="flex items-start space-x-3">
							<div className="w-12 h-16 bg-gradient-to-br from-teal-400 to-teal-600 rounded-md flex items-center justify-center flex-shrink-0">
								<BookOpen className="w-6 h-6 text-white" />
							</div>
							<div className="flex-1 min-w-0">
								<h4 className="font-semibold text-gray-900 text-sm leading-tight mb-1">
									{book.title}
								</h4>
								{book.authors && book.authors.length > 0 && (
									<div className="flex items-center space-x-1 text-xs text-gray-600 mb-2">
										<User className="w-3 h-3" />
										<span>{book.authors.map(author => author.name).join(', ')}</span>
									</div>
								)}
								{book.published_at && (
									<div className="flex items-center space-x-1 text-xs text-gray-500">
										<Calendar className="w-3 h-3" />
										<span>Published: {new Date(book.published_at).getFullYear()}</span>
									</div>
								)}
							</div>
						</div>
					</div>

					{/* Loan Duration Selection */}
					<div className="mb-6">
						<label className="block text-sm font-medium text-gray-700 mb-3">
							Loan Duration
						</label>
						<div className="grid grid-cols-2 gap-2">
							{[7, 14, 21, 30].map((duration) => (
								<button
									key={duration}
									onClick={() => onDurationChange(duration)}
									className={`
										p-3 rounded-lg border-2 transition-all duration-200 text-sm font-medium
										${loanDuration === duration
											? 'border-teal-500 bg-teal-50 text-teal-700'
											: 'border-gray-200 hover:border-gray-300 text-gray-700 hover:bg-gray-50'
										}
									`}
								>
									{duration} days
								</button>
							))}
						</div>
					</div>

					{/* Due Date Information */}
					<div className="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
						<div className="flex items-center space-x-2 mb-2">
							<Clock className="w-4 h-4 text-blue-600" />
							<span className="text-sm font-medium text-blue-900">Due Date</span>
						</div>
						<div className="flex items-center justify-between">
							<span className="text-lg font-semibold text-blue-900">
								{dueDate.toLocaleDateString('en-US', {
									weekday: 'long',
									year: 'numeric',
									month: 'long',
									day: 'numeric'
								})}
							</span>
							<div className="text-right">
								<div className="text-xs text-blue-600">
									{loanDuration} days from now
								</div>
								<div className="text-xs text-blue-500">
									{dueDate.toLocaleTimeString('en-US', {
										hour: '2-digit',
										minute: '2-digit'
									})}
								</div>
							</div>
						</div>
					</div>

					{/* Warning Message */}
					{isOverdue && (
						<div className="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
							<div className="flex items-center space-x-2">
								<AlertCircle className="w-4 h-4 text-red-600" />
								<span className="text-sm font-medium text-red-900">Important Notice</span>
							</div>
							<p className="text-sm text-red-700 mt-1">
								Please return this book on time to avoid late fees. You can renew your loan before the due date.
							</p>
						</div>
					)}

					{/* Terms and Conditions */}
					<div className="bg-gray-50 rounded-lg p-4 mb-6">
						<h5 className="text-sm font-medium text-gray-900 mb-2">Loan Terms</h5>
						<ul className="text-xs text-gray-600 space-y-1">
							<li>• Books must be returned on or before the due date</li>
							<li>• Late returns may incur fees</li>
							<li>• Books can be renewed up to 2 times</li>
							<li>• Damaged books must be reported immediately</li>
						</ul>
					</div>
				</div>

				{/* Footer */}
				<div className="flex space-x-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl">
					<button
						onClick={onClose}
						className="flex-1 bg-white hover:bg-gray-50 text-gray-700 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 border border-gray-300"
					>
						Cancel
					</button>
					<button
						onClick={onConfirm}
						disabled={isBorrowing}
						className="flex-1 bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-2"
					>
						{isBorrowing ? (
							<>
								<div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
								<span>Borrowing...</span>
							</>
						) : (
							<>
								<BookOpen className="w-4 h-4" />
								<span>Confirm Borrow</span>
							</>
						)}
					</button>
				</div>
			</div>

			{/* Backdrop click handler */}
			<div 
				className="absolute inset-0 -z-10" 
				onClick={onClose}
				aria-hidden="true"
			/>
		</div>
	);
}