import React from 'react';
import { Head } from '@inertiajs/react';
import Layout from '../Layout';

export default function Index() {
    return (
        <Layout>
            <Head title="Pagrindinis" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h1 className="text-2xl font-semibold mb-4">
                                Automobilių Serviso Valdymo Sistema
                            </h1>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div className="bg-blue-100 p-4 rounded-lg">
                                    <h2 className="text-lg font-semibold mb-2">Klientai</h2>
                                    <p>Valdykite klientų informaciją</p>
                                </div>
                                <div className="bg-green-100 p-4 rounded-lg">
                                    <h2 className="text-lg font-semibold mb-2">Automobiliai</h2>
                                    <p>Tvarkykite automobilių duomenis</p>
                                </div>
                                <div className="bg-yellow-100 p-4 rounded-lg">
                                    <h2 className="text-lg font-semibold mb-2">Paslaugos</h2>
                                    <p>Administruokite teikiamas paslaugas</p>
                                </div>
                                <div className="bg-purple-100 p-4 rounded-lg">
                                    <h2 className="text-lg font-semibold mb-2">Užsakymai</h2>
                                    <p>Sekite užsakymų eigą</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}