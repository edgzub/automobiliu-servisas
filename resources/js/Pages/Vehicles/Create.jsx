import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import Layout from '../Layout';

export default function Create({ clients }) {
    const { data, setData, post, processing, errors } = useForm({
        client_id: '',
        marke: '',
        modelis: '',
        metai: new Date().getFullYear(),
        valstybinis_numeris: '',
        vin_kodas: '',
    });

    function handleSubmit(e) {
        e.preventDefault();
        post(route('vehicles.store'));
    }

    return (
        <Layout>
            <Head title="Naujas Automobilis" />
            <div className="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <h1 className="text-2xl font-semibold mb-6">Naujo Automobilio Registracija</h1>

                        <form onSubmit={handleSubmit}>
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="client_id">
                                    Savininkas
                                </label>
                                <select
                                    id="client_id"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.client_id ? 'border-red-500' : ''
                                    }`}
                                    value={data.client_id}
                                    onChange={e => setData('client_id', e.target.value)}
                                >
                                    <option value="">Pasirinkite savininką</option>
                                    {clients.map(client => (
                                        <option key={client.id} value={client.id}>
                                            {client.vardas} {client.pavarde}
                                        </option>
                                    ))}
                                </select>
                                {errors.client_id && <p className="text-red-500 text-xs italic">{errors.client_id}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="marke">
                                    Markė
                                </label>
                                <input
                                    type="text"
                                    id="marke"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.marke ? 'border-red-500' : ''
                                    }`}
                                    value={data.marke}
                                    onChange={e => setData('marke', e.target.value)}
                                />
                                {errors.marke && <p className="text-red-500 text-xs italic">{errors.marke}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="modelis">
                                    Modelis
                                </label>
                                <input
                                    type="text"
                                    id="modelis"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.modelis ? 'border-red-500' : ''
                                    }`}
                                    value={data.modelis}
                                    onChange={e => setData('modelis', e.target.value)}
                                />
                                {errors.modelis && <p className="text-red-500 text-xs italic">{errors.modelis}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="metai">
                                    Metai
                                </label>
                                <input
                                    type="number"
                                    id="metai"
                                    min="1900"
                                    max={new Date().getFullYear() + 1}
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.metai ? 'border-red-500' : ''
                                    }`}
                                    value={data.metai}
                                    onChange={e => setData('metai', e.target.value)}
                                />
                                {errors.metai && <p className="text-red-500 text-xs italic">{errors.metai}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="valstybinis_numeris">
                                    Valstybinis numeris
                                </label>
                                <input
                                    type="text"
                                    id="valstybinis_numeris"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.valstybinis_numeris ? 'border-red-500' : ''
                                    }`}
                                    value={data.valstybinis_numeris}
                                    onChange={e => setData('valstybinis_numeris', e.target.value.toUpperCase())}
                                />
                                {errors.valstybinis_numeris && <p className="text-red-500 text-xs italic">{errors.valstybinis_numeris}</p>}
                            </div>

                            <div className="mb-6">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="vin_kodas">
                                    VIN kodas
                                </label>
                                <input
                                    type="text"
                                    id="vin_kodas"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.vin_kodas ? 'border-red-500' : ''
                                    }`}
                                    value={data.vin_kodas}
                                    onChange={e => setData('vin_kodas', e.target.value.toUpperCase())}
                                />
                                {errors.vin_kodas && <p className="text-red-500 text-xs italic">{errors.vin_kodas}</p>}
                            </div>

                            <div className="flex items-center justify-between">
                                <button
                                    type="submit"
                                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    disabled={processing}
                                >
                                    {processing ? 'Saugoma...' : 'Išsaugoti'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </Layout>
    );
}