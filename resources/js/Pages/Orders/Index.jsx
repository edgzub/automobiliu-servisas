import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Layout';
import StatusBadge from '@/Components/StatusBadge';
import OrderProgress from '@/Components/OrderProgress';
import { FaSearch, FaPlusCircle } from 'react-icons/fa';

export default function Index({ orders }) {
    const [search, setSearch] = useState('');
    const [sortField, setSortField] = useState('data');
    const [sortDirection, setSortDirection] = useState('desc');
    const [statusFilter, setStatusFilter] = useState('');

    // Unikalūs statusai
    const statuses = ['laukiama', 'vykdoma', 'atlikta', 'atsaukta'];

    // Filtravimo ir rūšiavimo logika
    const filteredOrders = orders
        .filter(order =>
            (statusFilter === '' || order.statusas === statusFilter) &&
            (
                order.vehicle.valstybinis_numeris.toLowerCase().includes(search.toLowerCase()) ||
                order.vehicle.client.vardas.toLowerCase().includes(search.toLowerCase()) ||
                order.vehicle.client.pavarde.toLowerCase().includes(search.toLowerCase()) ||
                order.service.pavadinimas.toLowerCase().includes(search.toLowerCase())
            )
        )
        .sort((a, b) => {
            const aValue = sortField === 'kaina' ? parseFloat(a[sortField]) : a[sortField];
            const bValue = sortField === 'kaina' ? parseFloat(b[sortField]) : b[sortField];

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

    const getStatusColor = (status) => {
        const colors = {
            laukiama: 'bg-yellow-100 text-yellow-800',
            vykdoma: 'bg-blue-100 text-blue-800',
            atlikta: 'bg-green-100 text-green-800',
            atsaukta: 'bg-red-100 text-red-800',
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    };

    return (
        <Layout>
            <Head title="Užsakymai" />
            <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-3xl font-bold text-gray-900">Užsakymų sąrašas</h1>
                    <Link
                        href={route('orders.create')}
                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Naujas užsakymas
                    </Link>
                </div>

                <div className="mb-4 flex gap-4">
                    <div className="flex-1">
                        <input
                            type="text"
                            placeholder="Ieškoti užsakymų..."
                            className="w-full px-4 py-2 border rounded-lg"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />
                    </div>
                    <div className="w-64">
                        <select
                            className="w-full px-4 py-2 border rounded-lg"
                            value={statusFilter}
                            onChange={(e) => setStatusFilter(e.target.value)}
                        >
                            <option value="">Visi statusai</option>
                            {statuses.map(status => (
                                <option key={status} value={status}>
                                    {status.charAt(0).toUpperCase() + status.slice(1)}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>

                <div className="bg-white shadow-md rounded-lg overflow-hidden">
                    <table className="min-w-full divide-y divide-gray-200">
                        <thead className="bg-gray-50">
                            <tr>
                                <th
                                    onClick={() => toggleSort('data')}
                                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                >
                                    Data {sortField === 'data' && (sortDirection === 'asc' ? '↑' : '↓')}
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Klientas
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Automobilis
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Paslauga
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statusas
                                </th>
                                <th
                                    onClick={() => toggleSort('kaina')}
                                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                >
                                    Kaina {sortField === 'kaina' && (sortDirection === 'asc' ? '↑' : '↓')}
                                </th>
                                <th className="px-6 py-3 relative">
                                    <span className="sr-only">Veiksmai</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody className="bg-white divide-y divide-gray-200">
                            {filteredOrders.map((order) => (
                                <tr key={order.id}>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm text-gray-900">
                                            {new Date(order.data).toLocaleDateString('lt-LT')}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm font-medium text-gray-900">
                                            {order.vehicle.client.vardas} {order.vehicle.client.pavarde}
                                        </div>
                                        <div className="text-sm text-gray-500">
                                            {order.vehicle.client.tel_numeris}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm text-gray-900">
                                            {order.vehicle.marke} {order.vehicle.modelis}
                                        </div>
                                        <div className="text-sm text-gray-500">
                                            {order.vehicle.valstybinis_numeris}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm text-gray-900">{order.service.pavadinimas}</div>
                                        <div className="text-sm text-gray-500">{order.service.kategorija}</div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <StatusBadge status={order.statusas} />
                                        <div className="mt-2">
                                            <OrderProgress status={order.statusas} />
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {parseFloat(order.kaina).toFixed(2)} €
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link
                                            href={route('orders.edit', order.id)}
                                            className="text-indigo-600 hover:text-indigo-900 mr-4"
                                        >
                                            Redaguoti
                                        </Link>
                                        <Link
                                            href={route('orders.destroy', order.id)}
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