// resources/js/Pages/Services/Index.jsx
import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Layout';
import ServiceCard from '@/Components/ServiceCard';
import { FaSearch, FaPlusCircle, FaFilter } from 'react-icons/fa';

export default function Index({ services }) {
    const [search, setSearch] = useState('');
    const [sortField, setSortField] = useState('pavadinimas');
    const [sortDirection, setSortDirection] = useState('asc');
    const [categoryFilter, setCategoryFilter] = useState('');
    const [viewMode, setViewMode] = useState('grid');

    // Gaukime unikalias kategorijas iš paslaugų sąrašo
    const categories = [...new Set(services.map(service => service.kategorija))];

    // Filtravimo ir rūšiavimo logika
    const filteredServices = services
        .filter(service =>
            (categoryFilter === '' || service.kategorija === categoryFilter) && // Kategorijos filtras
            (
                service.pavadinimas.toLowerCase().includes(search.toLowerCase()) ||
                service.aprasymas.toLowerCase().includes(search.toLowerCase()) ||
                service.kategorija.toLowerCase().includes(search.toLowerCase())
            )
        )
        .sort((a, b) => {
            const aValue = sortField === 'kaina' || sortField === 'trukme_valandomis'
                ? parseFloat(a[sortField])
                : a[sortField];
            const bValue = sortField === 'kaina' || sortField === 'trukme_valandomis'
                ? parseFloat(b[sortField])
                : b[sortField];

            if (sortDirection === 'asc') {
                return aValue > bValue ? 1 : -1;
            }
            return aValue < bValue ? 1 : -1;
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
            <Head title="Paslaugos" />
            <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-3xl font-bold text-gray-900">Paslaugų sąrašas</h1>
                    <Link
                        href={route('services.create')}
                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Pridėti paslaugą
                    </Link>
                </div>

                <div className="mb-6 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <div className="flex items-center bg-white rounded-lg shadow-sm overflow-hidden flex-grow">
                        <div className="px-3 py-2 text-gray-400">
                            <FaSearch />
                        </div>
                        <input
                            type="text"
                            placeholder="Ieškoti paslaugų..."
                            className="w-full py-2 px-2 border-0 focus:ring-0 focus:outline-none"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />
                    </div>
                    
                    <div className="flex items-center bg-white rounded-lg shadow-sm overflow-hidden">
                        <div className="px-3 py-2 text-gray-400">
                            <FaFilter />
                        </div>
                        <select
                            className="w-full md:w-64 py-2 px-2 border-0 focus:ring-0 focus:outline-none"
                            value={categoryFilter}
                            onChange={(e) => setCategoryFilter(e.target.value)}
                        >
                            <option value="">Visos kategorijos</option>
                            {categories.map(category => (
                                <option key={category} value={category}>
                                    {category}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>

                <div className="mb-4 flex justify-end">
                    <div className="flex rounded-lg overflow-hidden">
                        <button 
                            onClick={() => setViewMode('grid')}
                            className={`px-3 py-1 ${viewMode === 'grid' ? 'bg-blue-500 text-white' : 'bg-gray-200'}`}
                        >
                            Kortelės
                        </button>
                        <button 
                            onClick={() => setViewMode('table')}
                            className={`px-3 py-1 ${viewMode === 'table' ? 'bg-blue-500 text-white' : 'bg-gray-200'}`}
                        >
                            Lentelė
                        </button>
                    </div>
                </div>
                
                {viewMode === 'grid' ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredServices.map((service) => (
                            <ServiceCard key={service.id} service={service} />
                        ))}
                    </div>
                ) : (
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th
                                        onClick={() => toggleSort('pavadinimas')}
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    >
                                        Pavadinimas {sortField === 'pavadinimas' && (sortDirection === 'asc' ? '↑' : '↓')}
                                    </th>
                                    <th
                                        onClick={() => toggleSort('kategorija')}
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    >
                                        Kategorija {sortField === 'kategorija' && (sortDirection === 'asc' ? '↑' : '↓')}
                                    </th>
                                    <th
                                        onClick={() => toggleSort('kaina')}
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    >
                                        Kaina {sortField === 'kaina' && (sortDirection === 'asc' ? '↑' : '↓')}
                                    </th>
                                    <th
                                        onClick={() => toggleSort('trukme_valandomis')}
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    >
                                        Trukmė {sortField === 'trukme_valandomis' && (sortDirection === 'asc' ? '↑' : '↓')}
                                    </th>
                                    <th className="px-6 py-3 relative">
                                        <span className="sr-only">Veiksmai</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {filteredServices.map((service) => (
                                    <tr key={service.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm font-medium text-gray-900">{service.pavadinimas}</div>
                                            <div className="text-sm text-gray-500">{service.aprasymas}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {service.kategorija}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {(typeof service.kaina === 'number' ? service.kaina.toFixed(2) : parseFloat(service.kaina).toFixed(2))} €
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {service.trukme_valandomis} val.
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                href={route('services.edit', service.id)}
                                                className="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                Redaguoti
                                            </Link>
                                            <Link
                                                href={route('services.destroy', service.id)}
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
                )}
            </div>
        </Layout>
    );
}