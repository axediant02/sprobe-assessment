'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function HomePage() {
  const [searchFilters, setSearchFilters] = useState({
    category: '',
    location: '',
    subscription: '',
    sortBy: ''
  });

  const router = useRouter();

  const handleLogin = () => {
    router.push('/login');
  };

  const handleStartNow = () => {
    router.push('/register');
  };

  const handleSearchFilterChange = (field: string, value: string) => {
    setSearchFilters(prev => ({
      ...prev,
      [field]: value
    }));
  };

  return (
    <div className="min-h-screen bg-teal-600">
      <header className="bg-teal-600 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <h1 className="text-2xl font-bold text-white">Genius</h1>
            </div>

            <nav className="hidden md:flex space-x-8">
              <a href="#" className="text-white hover:text-teal-200 transition-colors">Menu</a>
              <a href="#" className="text-white hover:text-teal-200 transition-colors">News</a>
              <a href="#" className="text-white hover:text-teal-200 transition-colors">Legal</a>
              <a href="#" className="text-white hover:text-teal-200 transition-colors">History</a>
              <a href="#" className="text-white hover:text-teal-200 transition-colors">Pricing</a>
              <a href="#" className="text-white hover:text-teal-200 transition-colors">Contact</a>
            </nav>

            <div className="flex items-center space-x-4">
              <button className="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors">
                Buy
              </button>
              <button 
                onClick={handleLogin}
                className="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors"
              >
                Login
              </button>
            </div>
          </div>
        </div>
      </header>

      <section className="bg-teal-600 text-white py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <h1 className="text-5xl font-bold mb-6 leading-tight">
                Discover Our Library of Amazing Books
              </h1>
              <p className="text-xl mb-8 text-teal-100">
                Explore thousands of books across all genres. From fiction to non-fiction, 
                find your next favorite read in our extensive collection.
              </p>
              <button 
                onClick={handleStartNow}
                className="bg-green-700 hover:bg-green-800 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors"
              >
                Start Now
              </button>
            </div>

            <div className="flex justify-center">
              <div className="relative">
                <div className="relative w-64 h-80">
                  <div className="absolute bottom-0 left-0 w-48 h-72 bg-red-600 rounded-r-md shadow-lg transform rotate-12"></div>
                  <div className="absolute bottom-0 left-4 w-48 h-72 bg-blue-600 rounded-r-md shadow-lg transform rotate-6"></div>
                  <div className="absolute bottom-0 left-8 w-48 h-72 bg-yellow-500 rounded-r-md shadow-lg">
                    <div className="p-4 text-center">
                      <div className="text-xs font-bold text-gray-800">COVER FOUR</div>
                      <div className="text-xs text-gray-600 mt-1">FILLES ROUND</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="bg-teal-600 text-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-8">
            <h2 className="text-3xl font-bold mb-4">Find Your Perfect Books</h2>
            <p className="text-teal-100">Filter and discover books that match your interests</p>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
            <div className="relative">
              <select 
                value={searchFilters.category}
                onChange={(e) => handleSearchFilterChange('category', e.target.value)}
                className="w-full px-4 py-3 bg-white text-gray-800 rounded-lg appearance-none cursor-pointer"
              >
                <option value="">Category</option>
                <option value="fiction">Fiction</option>
                <option value="non-fiction">Non-Fiction</option>
                <option value="science">Science</option>
                <option value="history">History</option>
              </select>
              <div className="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                <svg className="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>

            <div className="relative">
              <select 
                value={searchFilters.location}
                onChange={(e) => handleSearchFilterChange('location', e.target.value)}
                className="w-full px-4 py-3 bg-white text-gray-800 rounded-lg appearance-none cursor-pointer"
              >
                <option value="">Location</option>
                <option value="main-library">Main Library</option>
                <option value="branch-1">Branch 1</option>
                <option value="branch-2">Branch 2</option>
                <option value="online">Online</option>
              </select>
              <div className="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                <svg className="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>

            <div className="relative">
              <select 
                value={searchFilters.subscription}
                onChange={(e) => handleSearchFilterChange('subscription', e.target.value)}
                className="w-full px-4 py-3 bg-white text-gray-800 rounded-lg appearance-none cursor-pointer"
              >
                <option value="">Subscription</option>
                <option value="free">Free</option>
                <option value="premium">Premium</option>
                <option value="student">Student</option>
              </select>
              <div className="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                <svg className="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>

            <div className="relative">
              <select 
                value={searchFilters.sortBy}
                onChange={(e) => handleSearchFilterChange('sortBy', e.target.value)}
                className="w-full px-4 py-3 bg-white text-gray-800 rounded-lg appearance-none cursor-pointer"
              >
                <option value="">Sort by</option>
                <option value="title">Title</option>
                <option value="author">Author</option>
                <option value="rating">Rating</option>
                <option value="date">Date</option>
              </select>
              <div className="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                <svg className="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="bg-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">Your Top Rated Books</h2>
            <p className="text-gray-600">Discover the most popular books in our collection</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div className="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
              <div className="w-full h-48 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg mb-4 flex items-center justify-center">
                <span className="text-white font-bold text-lg">FLAVER PILLANO</span>
              </div>
              <h3 className="font-semibold text-gray-900 mb-2">FLAVER PILLANO</h3>
              <div className="flex items-center mb-2">
                <div className="flex text-yellow-400">
                  {[...Array(5)].map((_, i) => (
                    <svg key={i} className={`w-4 h-4 ${i < 4 ? 'text-yellow-400' : 'text-gray-300'}`} fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  ))}
                </div>
                <span className="ml-2 text-sm text-gray-600">4.5</span>
              </div>
              <div className="text-right">
                <span className="text-lg font-bold text-green-600">$19</span>
              </div>
            </div>

            <div className="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
              <div className="w-full h-48 bg-gradient-to-br from-green-400 to-green-600 rounded-lg mb-4 flex items-center justify-center">
                <span className="text-white font-bold text-lg">JULIA HALL</span>
              </div>
              <h3 className="font-semibold text-gray-900 mb-2">JULIA HALL</h3>
              <div className="flex items-center mb-2">
                <div className="flex text-yellow-400">
                  {[...Array(5)].map((_, i) => (
                    <svg key={i} className={`w-4 h-4 ${i < 4 ? 'text-yellow-400' : 'text-gray-300'}`} fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  ))}
                </div>
                <span className="ml-2 text-sm text-gray-600">4.8</span>
              </div>
              <div className="text-right">
                <span className="text-lg font-bold text-green-600">$24</span>
              </div>
            </div>

            <div className="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
              <div className="w-full h-48 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg mb-4 flex items-center justify-center">
                <span className="text-white font-bold text-lg">LIGHT SQUARE</span>
              </div>
              <h3 className="font-semibold text-gray-900 mb-2">LIGHT SQUARE</h3>
              <div className="flex items-center mb-2">
                <div className="flex text-yellow-400">
                  {[...Array(5)].map((_, i) => (
                    <svg key={i} className={`w-4 h-4 ${i < 4 ? 'text-yellow-400' : 'text-gray-300'}`} fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  ))}
                </div>
                <span className="ml-2 text-sm text-gray-600">4.2</span>
              </div>
              <div className="text-right">
                <span className="text-lg font-bold text-green-600">$16</span>
              </div>
            </div>

            <div className="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
              <div className="w-full h-48 bg-gradient-to-br from-red-400 to-red-600 rounded-lg mb-4 flex items-center justify-center">
                <span className="text-white font-bold text-lg">DARK NIGHT</span>
              </div>
              <h3 className="font-semibold text-gray-900 mb-2">DARK NIGHT</h3>
              <div className="flex items-center mb-2">
                <div className="flex text-yellow-400">
                  {[...Array(5)].map((_, i) => (
                    <svg key={i} className={`w-4 h-4 ${i < 4 ? 'text-yellow-400' : 'text-gray-300'}`} fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  ))}
                </div>
                <span className="ml-2 text-sm text-gray-600">4.7</span>
              </div>
              <div className="text-right">
                <span className="text-lg font-bold text-green-600">$21</span>
              </div>
            </div>
          </div>

          <div className="text-center mt-8">
            <button className="w-12 h-12 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors">
              <svg className="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </section>

      <section className="bg-gray-50 py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">Featured Books</h2>
            <p className="text-gray-600">Discover our curated collection of must-read books</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-blue-800 text-white rounded-lg p-8 hover:shadow-xl transition-shadow">
              <h3 className="text-xl font-bold mb-4">What Your Mind Thinks About Reading</h3>
              <ul className="space-y-2 mb-6">
                <li className="flex items-center">
                  <div className="w-2 h-2 bg-white rounded-full mr-3"></div>
                  Expand your knowledge
                </li>
                <li className="flex items-center">
                  <div className="w-2 h-2 bg-white rounded-full mr-3"></div>
                  Improve vocabulary
                </li>
                <li className="flex items-center">
                  <div className="w-2 h-2 bg-white rounded-full mr-3"></div>
                  Reduce stress levels
                </li>
              </ul>
              <div className="flex space-x-2">
                <div className="w-8 h-8 bg-blue-600 rounded-full"></div>
                <div className="w-8 h-8 bg-blue-600 rounded-full"></div>
                <div className="w-8 h-8 bg-blue-600 rounded-full"></div>
              </div>
            </div>

            <div className="bg-red-500 text-white rounded-lg p-8 hover:shadow-xl transition-shadow">
              <h3 className="text-xl font-bold mb-4">Start A New Reading Journey</h3>
              <p className="mb-6">
                Embark on an adventure through different worlds and perspectives. 
                Reading opens doors to new experiences and knowledge.
              </p>
              <button className="bg-white text-red-500 px-6 py-2 rounded-md font-semibold hover:bg-gray-100 transition-colors">
                Get Started
              </button>
            </div>

            <div className="bg-teal-500 text-white rounded-lg p-8 hover:shadow-xl transition-shadow">
              <h3 className="text-xl font-bold mb-4">Build Your Reading Habit</h3>
              <p className="mb-6">
                Create a sustainable reading routine that fits your lifestyle. 
                Track your progress and achieve your reading goals.
              </p>
              <button className="bg-white text-teal-500 px-6 py-2 rounded-md font-semibold hover:bg-gray-100 transition-colors">
                Learn More
              </button>
            </div>
          </div>
        </div>
      </section>

      <section className="bg-teal-600 text-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-8">
            <h2 className="text-3xl font-bold mb-4">Where new ideas & best books</h2>
            <p className="text-teal-100">Stay updated with our latest book recommendations and reading tips</p>
          </div>

          <div className="max-w-md mx-auto">
            <div className="flex">
              <input
                type="email"
                placeholder="Your email address"
                className="flex-1 px-4 py-3 rounded-l-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white"
              />
              <button className="bg-gray-200 text-gray-800 px-6 py-3 rounded-r-lg hover:bg-gray-300 transition-colors">
                Subscribe
              </button>
            </div>
          </div>
        </div>
      </section>

      <footer className="bg-white py-12 border-t">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
              <h3 className="text-xl font-bold text-gray-900 mb-4">Genius</h3>
              <p className="text-gray-600 mb-4">
                Your ultimate destination for discovering and reading amazing books.
              </p>
              <div className="flex space-x-4">
                <a href="#" className="text-gray-400 hover:text-gray-600">
                  <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                  </svg>
                </a>
                <a href="#" className="text-gray-400 hover:text-gray-600">
                  <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                  </svg>
                </a>
              </div>
            </div>

            <div>
              <h4 className="font-semibold text-gray-900 mb-4">Quick Links</h4>
              <ul className="space-y-2">
                <li><a href="#" className="text-gray-600 hover:text-gray-900">Books</a></li>
                <li><a href="#" className="text-gray-600 hover:text-gray-900">Authors</a></li>
                <li><a href="#" className="text-gray-600 hover:text-gray-900">Categories</a></li>
                <li><a href="#" className="text-gray-600 hover:text-gray-900">Popular</a></li>
              </ul>
            </div>

            <div>
              <h4 className="font-semibold text-gray-900 mb-4">Support</h4>
              <ul className="space-y-2">
                <li><a href="#" className="text-gray-600 hover:text-gray-900">Help Center</a></li>
                <li><a href="#" className="text-gray-600 hover:text-gray-900">Contact Us</a></li>
                <li><a href="#" className="text-gray-600 hover:text-gray-900">Privacy Policy</a></li>
                <li><a href="#" className="text-gray-600 hover:text-gray-900">Terms of Service</a></li>
              </ul>
            </div>

            <div>
              <h4 className="font-semibold text-gray-900 mb-4">Contact</h4>
              <ul className="space-y-2">
                <li className="text-gray-600">📧 ianfredcaballero@gmail.com</li>
                <li className="text-gray-600">📞 +639765407577</li>
                <li className="text-gray-600">📍 Toledo City, Cebu</li>
              </ul>
            </div>
          </div>

          <div className="mt-8 pt-8 border-t border-gray-200 text-center">
            <p className="text-gray-600">
              © 2025 Library Management System. All rights reserved.
            </p>
          </div>
        </div>
      </footer>
    </div>
  );
}