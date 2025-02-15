import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import Layout from '../Layout';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <Layout>
            <Head title="Prisijungimas" />
            <div className="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-md">
                    <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Prisijungimas
                    </h2>
                </div>

                <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                    <div className="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                        <form onSubmit={handleSubmit}>
                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                    El. paštas
                                </label>
                                <div className="mt-1">
                                    <input
                                        id="email"
                                        type="email"
                                        required
                                        value={data.email}
                                        onChange={e => setData('email', e.target.value)}
                                        className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    />
                                    {errors.email && <p className="mt-2 text-sm text-red-600">{errors.email}</p>}
                                </div>
                            </div>

                            <div className="mt-6">
                                <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                                    Slaptažodis
                                </label>
                                <div className="mt-1">
                                    <input
                                        id="password"
                                        type="password"
                                        required
                                        value={data.password}
                                        onChange={e => setData('password', e.target.value)}
                                        className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    />
                                    {errors.password && <p className="mt-2 text-sm text-red-600">{errors.password}</p>}
                                </div>
                            </div>

                            <div className="mt-6">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    {processing ? 'Jungiamasi...' : 'Prisijungti'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </Layout>
    );
}