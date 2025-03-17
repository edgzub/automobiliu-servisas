// resources/js/Pages/Orders/Edit.jsx
import React, { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import Layout from '../Layout';

export default function Edit({ order = {}, clients = [], services = [] }) {
    const [selectedClientVehicles, setSelectedClientVehicles] = useState([]);
    const [selectedService, setSelectedService] = useState(null);

    const { data, setData, put, processing, errors } = useForm({
        client_id: order?.vehicle?.client_id || '',
        vehicle_id: order?.vehicle_id || '',
        service_id: order?.service_id || '',
        data: order?.data?.split('T')[0] || new Date().toISOString().split('T')[0],
        statusas: order?.statusas || 'laukiama',
        komentarai: order?.komentarai || '',
        kaina: order?.kaina || '',
    });
    // Kai pasikeičia klientas, atnaujiname jo automobilių sąrašą
    useEffect(() => {
        if (data.client_id && clients?.length) {
            const client = clients.find(c => c.id.toString() === data.client_id.toString());
            setSelectedClientVehicles(client?.vehicles || []);
        }
    }, [data.client_id, clients]);

    useEffect(() => {
        if (data.service_id && services?.length) {
            const service = services.find(s => s.id.toString() === data.service_id.toString());
            setSelectedService(service);
            if (!order?.kaina) {
                setData('kaina', service?.kaina?.toString() || '');
            }
        }
    }, [data.service_id, services]);

    function handleSubmit(e) {
        e.preventDefault();
        put(route('orders.update', order.id));
    }

    return (
        <Layout>
            <Head title="Redaguoti Užsakymą" />
            <div className="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <h1 className="text-2xl font-semibold mb-6">Redaguoti Užsakymą</h1>

                        <form onSubmit={handleSubmit}>
                            {/* Kliento pasirinkimas */}
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="client_id">
                                    Klientas
                                </label>
                                <select
                                    id="client_id"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.client_id ? 'border-red-500' : ''
                                        }`}
                                    value={data.client_id}
                                    onChange={e => setData('client_id', e.target.value)}
                                >
                                    <option value="">Pasirinkite klientą</option>
                                    {clients.map(client => (
                                        <option key={client.id} value={client.id}>
                                            {client.vardas} {client.pavarde} - {client.tel_numeris}
                                        </option>
                                    ))}
                                </select>
                                {errors.client_id && <p className="text-red-500 text-xs italic">{errors.client_id}</p>}
                            </div>

                            {/* Automobilio pasirinkimas */}
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="vehicle_id">
                                    Automobilis
                                </label>
                                <select
                                    id="vehicle_id"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.vehicle_id ? 'border-red-500' : ''
                                        }`}
                                    value={data.vehicle_id}
                                    onChange={e => setData('vehicle_id', e.target.value)}
                                    disabled={!data.client_id}
                                >
                                    <option value="">Pasirinkite automobilį</option>
                                    {selectedClientVehicles.map(vehicle => (
                                        <option key={vehicle.id} value={vehicle.id}>
                                            {vehicle.marke} {vehicle.modelis} - {vehicle.valstybinis_numeris}
                                        </option>
                                    ))}
                                </select>
                                {errors.vehicle_id && <p className="text-red-500 text-xs italic">{errors.vehicle_id}</p>}
                            </div>

                            {/* Paslaugos pasirinkimas */}
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="service_id">
                                    Paslauga
                                </label>
                                <select
                                    id="service_id"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.service_id ? 'border-red-500' : ''
                                        }`}
                                    value={data.service_id}
                                    onChange={e => setData('service_id', e.target.value)}
                                >
                                    <option value="">Pasirinkite paslaugą</option>
                                    {services?.map(service => (
                                        <option key={service.id} value={service.id}>
                                            {service.pavadinimas} - {parseFloat(service.kaina).toFixed(2)} €
                                        </option>
                                    ))}
                                </select>
                                {errors.service_id && <p className="text-red-500 text-xs italic">{errors.service_id}</p>}
                            </div>

                            {/* Data */}
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="data">
                                    Data
                                </label>
                                <input
                                    type="date"
                                    id="data"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.data ? 'border-red-500' : ''
                                        }`}
                                    value={data.data}
                                    onChange={e => setData('data', e.target.value)}
                                />
                                {errors.data && <p className="text-red-500 text-xs italic">{errors.data}</p>}
                            </div>

                            {/* Statusas */}
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="statusas">
                                    Statusas
                                </label>
                                <select
                                    id="statusas"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.statusas ? 'border-red-500' : ''
                                        }`}
                                    value={data.statusas}
                                    onChange={e => setData('statusas', e.target.value)}
                                >
                                    <option value="laukiama">Naujas</option>
                                    <option value="vykdoma">Vykdomas</option>
                                    <option value="atlikta">Baigtas</option>
                                    <option value="atsaukta">Atšauktas</option>
                                </select>
                                {errors.statusas && <p className="text-red-500 text-xs italic">{errors.statusas}</p>}
                            </div>

                            {/* Komentarai */}
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="komentarai">
                                    Komentarai
                                </label>
                                <textarea
                                    id="komentarai"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.komentarai ? 'border-red-500' : ''
                                        }`}
                                    value={data.komentarai}
                                    onChange={e => setData('komentarai', e.target.value)}
                                    rows="3"
                                />
                                {errors.komentarai && <p className="text-red-500 text-xs italic">{errors.komentarai}</p>}
                            </div>

                            {/* Kaina */}
                            <div className="mb-6">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="kaina">
                                    Kaina (€)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    id="kaina"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.kaina ? 'border-red-500' : ''
                                        }`}
                                    value={data.kaina}
                                    onChange={e => setData('kaina', e.target.value)}
                                />
                                {errors.kaina && <p className="text-red-500 text-xs italic">{errors.kaina}</p>}
                            </div>

                            <div className="flex items-center justify-between">
                                <button
                                    type="submit"
                                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    disabled={processing}
                                >
                                    {processing ? 'Saugoma...' : 'Atnaujinti'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </Layout>
    );
}