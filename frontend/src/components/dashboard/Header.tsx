'use client';
import React from 'react';

interface HeaderProps {
	userName: string;
	onLogout: () => void;
}

export default function Header({ userName, onLogout }: HeaderProps) {
	return (
		<header className="bg-teal-600 text-white shadow-lg">
			<div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div className="flex justify-between items-center h-16">
					<div className="flex items-center">
						<h1 className="text-2xl font-bold text-white">Genius Library</h1>
					</div>
					<div className="flex items-center space-x-4">
						<span className="text-teal-100">Welcome, {userName}</span>
						<button
							onClick={onLogout}
							className="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
						>
							Logout
						</button>
					</div>
				</div>
			</div>
		</header>
	);
}