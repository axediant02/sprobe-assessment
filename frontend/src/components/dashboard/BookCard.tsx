'use client';
import React from 'react';

interface Book {
	id: number;
	title: string;
	description: string;
	published_at?: string;
	authors?: Array<{ name: string }>;
}

interface BookCardProps {
	book: Book;
	onBorrow: (book: Book) => void;
	isBorrowing: boolean;
}

export default function BookCard({ book, onBorrow, isBorrowing }: BookCardProps) {
	return (
		<div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
			<div className="w-full h-48 bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
				<svg className="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
				</svg>
			</div>
			<div className="p-4">
				<h3 className="font-semibold text-gray-900 text-lg mb-2 line-clamp-2">{book.title}</h3>
				{book.authors && book.authors.length > 0 && (
					<p className="text-sm text-gray-600 mb-2">By {book.authors.map(author => author.name).join(', ')}</p>
				)}
				{book.description && (
					<p className="text-sm text-gray-500 mb-3 line-clamp-2">{book.description}</p>
				)}
				{book.published_at && (
					<p className="text-xs text-gray-400 mb-3">Published: {new Date(book.published_at).getFullYear()}</p>
				)}
				<button
					onClick={() => onBorrow(book)}
					disabled={isBorrowing}
					className="w-full bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
				>
					{isBorrowing ? 'Borrowing...' : 'Borrow Book'}
				</button>
			</div>
		</div>
	);
}