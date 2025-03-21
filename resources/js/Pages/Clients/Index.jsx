import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Layout';

export default function Index({ clients }) {
    const [search, setSearch] = useState('');
    const [sortField, setSortField] = useState('pavarde');
    const [sortDirection, setSortDirection] = useState('asc');

    const filteredClients = clients
        .filter(client => 
            client.vardas.toLowerCase().includes(search.toLowerCase()) ||
            client.pavarde.toLowerCase().includes(search.toLowerCase()) ||
            client.el_pastas.toLowerCase().includes(search.toLowerCase())
        )
        .sort((a, b) => {
            if (sortDirection === 'asc') {
                return a[sortField] > b[sortField] ? 1 : -1;
            }
            return a[sortField] < b[sortField] ? 1 : -1;
        });

    const toggleSort = (field) => {
        if (field === sortField) {
            setSortDirection(sortDirection === 'asc' ? 'desc' : 'asc');
        } else {
            setSortField(field);
            setSortDirection('asc');
        }
    };

    return (
        <Layout>
            <Head title="Klientai" />
            <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-3xl font-bold text-gray-900">Klientų sąrašas</h1>
                    <Link
                        href={route('clients.create')}
                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Pridėti klientą
                    </Link>
                </div>

                <div className="mb-4">
                    <input
                        type="text"
                        placeholder="Ieškoti klientų..."
                        className="w-full px-4 py-2 border rounded-lg"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                    />
                </div>

                <div className="bg-white shadow-md rounded-lg overflow-hidden">
                    <table className="min-w-full divide-y divide-gray-200">
                        <thead className="bg-gray-50">
                            <tr>
                                <th
                                    onClick={() => toggleSort('pavarde')}
                                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                >
                                    Pavardė {sortField === 'pavarde' && (sortDirection === 'asc' ? '↑' : '↓')}
                                </th>
                                <th
                                    onClick={() => toggleSort('vardas')}
                                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                >
                                    Vardas {sortField === 'vardas' && (sortDirection === 'asc' ? '↑' : '↓')}
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontaktai
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Automobiliai
                                </th>
                                <th className="px-6 py-3 relative">
                                    <span className="sr-only">Veiksmai</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody className="bg-white divide-y divide-gray-200">
                            {filteredClients.map((client) => (
                                <tr key={client.id}>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm font-medium text-gray-900">{client.pavarde}</div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm text-gray-900">{client.vardas}</div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm text-gray-500">{client.el_pastas}</div>
                                        <div className="text-sm text-gray-500">{client.tel_numeris}</div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {client.vehicles?.length || 0} automobiliai
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link
                                            href={route('clients.edit', client.id)}
                                            className="text-indigo-600 hover:text-indigo-900 mr-4"
                                        >
                                            Redaguoti
                                        </Link>
                                        <Link
                                            href={route('clients.destroy', client.id)}
                                            method="delete"
                                            as="button"
                                            className="text-red-600 hover:text-red-900"
                                        >
                                            Ištrinti
                                        </Link>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </Layout>
    );
}