import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import Layout from '../Layout';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        vardas: '',
        pavarde: '',
        tel_numeris: '',
        el_pastas: '',
    });

    function handleSubmit(e) {
        e.preventDefault();
        post(route('clients.store'));
    }

    return (
        <Layout>
            <Head title="Naujas Klientas" />
            <div className="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <h1 className="text-2xl font-semibold mb-6">Naujo Kliento Registracija</h1>

                        <form onSubmit={handleSubmit}>
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="vardas">
                                    Vardas
                                </label>
                                <input
                                    type="text"
                                    id="vardas"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.vardas ? 'border-red-500' : ''
                                    }`}
                                    value={data.vardas}
                                    onChange={e => setData('vardas', e.target.value)}
                                />
                                {errors.vardas && <p className="text-red-500 text-xs italic">{errors.vardas}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="pavarde">
                                    Pavardė
                                </label>
                                <input
                                    type="text"
                                    id="pavarde"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.pavarde ? 'border-red-500' : ''
                                    }`}
                                    value={data.pavarde}
                                    onChange={e => setData('pavarde', e.target.value)}
                                />
                                {errors.pavarde && <p className="text-red-500 text-xs italic">{errors.pavarde}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="tel_numeris">
                                    Telefono numeris
                                </label>
                                <input
                                    type="text"
                                    id="tel_numeris"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.tel_numeris ? 'border-red-500' : ''
                                    }`}
                                    value={data.tel_numeris}
                                    onChange={e => setData('tel_numeris', e.target.value)}
                                />
                                {errors.tel_numeris && <p className="text-red-500 text-xs italic">{errors.tel_numeris}</p>}
                            </div>

                            <div className="mb-6">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="el_pastas">
                                    El. paštas
                                </label>
                                <input
                                    type="email"
                                    id="el_pastas"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.el_pastas ? 'border-red-500' : ''
                                    }`}
                                    value={data.el_pastas}
                                    onChange={e => setData('el_pastas', e.target.value)}
                                />
                                {errors.el_pastas && <p className="text-red-500 text-xs italic">{errors.el_pastas}</p>}
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