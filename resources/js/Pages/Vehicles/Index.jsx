import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Layout';
import VehicleCard from '@/Components/VehicleCard';
import { FaSearch, FaPlusCircle } from 'react-icons/fa';

export default function Index({ vehicles }) {
    const [search, setSearch] = useState('');
    const [sortField, setSortField] = useState('marke');
    const [sortDirection, setSortDirection] = useState('asc');
    const [viewMode, setViewMode] = useState('grid');

    const filteredVehicles = vehicles
        .filter(vehicle => 
            vehicle.marke.toLowerCase().includes(search.toLowerCase()) ||
            vehicle.modelis.toLowerCase().includes(search.toLowerCase()) ||
            vehicle.valstybinis_numeris.toLowerCase().includes(search.toLowerCase()) ||
            vehicle.client.vardas.toLowerCase().includes(search.toLowerCase()) ||
            vehicle.client.pavarde.toLowerCase().includes(search.toLowerCase())
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
            <Head title="Automobiliai" />
            <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-3xl font-bold text-gray-900">Automobilių sąrašas</h1>
                    <Link
                        href={route('vehicles.create')}
                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center"
                    >
                        <FaPlusCircle className="mr-2" />
                        Pridėti automobilį
                    </Link>
                </div>

                <div className="mb-6">
                    <div className="flex items-center bg-white rounded-lg shadow-sm overflow-hidden">
                        <div className="px-3 py-2 text-gray-400">
                            <FaSearch />
                        </div>
                        <input
                            type="text"
                            placeholder="Ieškoti automobilių..."
                            className="w-full py-2 px-2 border-0 focus:ring-0 focus:outline-none"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />
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
                        {filteredVehicles.map((vehicle) => (
                            <VehicleCard key={vehicle.id} vehicle={vehicle} />
                        ))}
                    </div>
                ) : (
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th 
                                        onClick={() => toggleSort('marke')}
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    >
                                        Markė {sortField === 'marke' && (sortDirection === 'asc' ? '↑' : '↓')}
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Modelis
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Metai
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Valst. numeris
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Savininkas
                                    </th>
                                    <th className="px-6 py-3 relative">
                                        <span className="sr-only">Veiksmai</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {filteredVehicles.map((vehicle) => (
                                    <tr key={vehicle.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm font-medium text-gray-900">{vehicle.marke}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm text-gray-900">{vehicle.modelis}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm text-gray-500">{vehicle.metai}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm text-gray-500">{vehicle.valstybinis_numeris}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm text-gray-900">
                                                {vehicle.client.vardas} {vehicle.client.pavarde}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                href={route('vehicles.edit', vehicle.id)}
                                                className="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                Redaguoti
                                            </Link>
                                            <Link
                                                href={route('vehicles.destroy', vehicle.id)}
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