import React from 'react';
import { Head } from '@inertiajs/react';
import Layout from '../Layout';

export default function Index() {
    return (
        <Layout>
            <Head title="Pagrindinis" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                        <div className="p-6 bg-gradient-to-r from-gray-100 to-gray-200 border-b border-gray-200">
                            <h1 className="text-3xl font-bold mb-6 text-center text-gray-800">
                                Automobilių Serviso Valdymo Sistema
                            </h1>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div className="bg-gradient-to-r from-blue-400 to-blue-500 p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                                    <h2 className="text-xl font-semibold mb-2 text-white">Klientai</h2>
                                    <p className="text-white">Valdykite klientų informaciją</p>
                                </div>
                                <div className="bg-gradient-to-r from-green-400 to-green-500 p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                                    <h2 className="text-xl font-semibold mb-2 text-white">Automobiliai</h2>
                                    <p className="text-white">Tvarkykite automobilių duomenis</p>
                                </div>
                                <div className="bg-gradient-to-r from-yellow-400 to-yellow-500 p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                                    <h2 className="text-xl font-semibold mb-2 text-white">Paslaugos</h2>
                                    <p className="text-white">Administruokite teikiamas paslaugas</p>
                                </div>
                                <div className="bg-gradient-to-r from-purple-400 to-purple-500 p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                                    <h2 className="text-xl font-semibold mb-2 text-white">Užsakymai</h2>
                                    <p className="text-white">Sekite užsakymų eigą</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}