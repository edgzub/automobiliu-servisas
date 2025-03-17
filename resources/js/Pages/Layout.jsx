import React, { useState } from 'react';
import { Link, usePage, router as Inertia } from '@inertiajs/react';
import { Toast } from '@/Components/Toast';

export default function Layout({ children }) {
    const { auth } = usePage().props;
    const [showDropdown, setShowDropdown] = useState(false);

    const toggleDropdown = () => {
        setShowDropdown(!showDropdown);
    };

    return (
        <div className="min-h-screen bg-gray-100">
            <Toast />
            <nav className="bg-white shadow-lg">
                <div className="max-w-7xl mx-auto px-4">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <div className="flex-shrink-0 flex items-center">
                                <Link href="/" className="text-2xl font-bold tracking-wider">
                                    <span className="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-800">
                                        AUTO
                                    </span>
                                    <span className="ml-2 bg-clip-text text-transparent bg-gradient-to-r from-gray-700 to-gray-900">
                                        SERVISAS
                                    </span>
                                </Link>
                            </div>
                            <div className="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <Link
                                    href="/clients"
                                    className="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300"
                                >
                                    Klientai
                                </Link>
                                <Link
                                    href="/vehicles"
                                    className="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300"
                                >
                                    Automobiliai
                                </Link>
                                <Link
                                    href="/services"
                                    className="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300"
                                >
                                    Paslaugos
                                </Link>
                                <Link
                                    href="/orders"
                                    className="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300"
                                >
                                    Užsakymai
                                </Link>
                            </div>
                        </div>

                        <div className="flex items-center">
                            {auth?.user ? (
                                <div className="relative">
                                    <button
                                        onClick={toggleDropdown}
                                        type="button"
                                        className="flex items-center max-w-xs rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <svg 
                                            className="h-8 w-8 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path 
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                        <span className="ml-2 text-gray-700">{auth.user.name}</span>
                                    </button>

                                    {showDropdown && (
                                        <div className="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                            <Link
                                                href="/profile"
                                                className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            >
                                                Mano Profilis
                                            </Link>
                                            <button
                                                onClick={() => Inertia.post('/logout')}
                                                className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            >
                                                Atsijungti
                                            </button>
                                        </div>
                                    )}
                                </div>
                            ) : (
                                <div className="flex space-x-4">
                                    <Link
                                        href="/login"
                                        className="text-gray-900 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
                                    >
                                        Prisijungti
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium"
                                    >
                                        Registruotis
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            <main>{children}</main>
        </div>
    );
}