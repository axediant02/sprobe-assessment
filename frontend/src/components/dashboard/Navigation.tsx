'use client';
import React from 'react';

interface NavigationProps {
	activeTab: string;
	onTabChange: (tab: string) => void;
}

export default function Navigation({ activeTab, onTabChange }: NavigationProps) {
	const tabs = [
		{ id: 'overview', label: 'Browse Books' },
		{ id: 'books', label: 'My Books' },
		{ id: 'loans', label: 'My Loans' },
		{ id: 'profile', label: 'Profile' },
	];

	return (
		<nav className="bg-white shadow-sm border-b">
			<div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div className="flex space-x-8">
					{tabs.map((tab) => (
						<button
							key={tab.id}
							onClick={() => onTabChange(tab.id)}
							className={`py-4 px-1 border-b-2 font-medium text-sm ${
								activeTab === tab.id
									? 'border-teal-500 text-teal-600'
									: 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
							}`}
						>
							{tab.label}
						</button>
					))}
				</div>
			</div>
		</nav>
	);
}
