// resources/js/Pages/Services/Edit.jsx
import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import Layout from '../Layout';

export default function Edit({ service }) {
    const { data, setData, put, processing, errors } = useForm({
        pavadinimas: service.pavadinimas || '',
        aprasymas: service.aprasymas || '',
        kaina: service.kaina || '',
        trukme_valandomis: service.trukme_valandomis || '',
        kategorija: service.kategorija || '',
    });

    function handleSubmit(e) {
        e.preventDefault();
        put(route('services.update', service.id));
    }

    // Paslaugų kategorijų sąrašas
    const kategorijos = [
        'Techninė priežiūra',
        'Variklio remontas',
        'Važiuoklės remontas',
        'Stabdžių sistema',
        'Elektros sistema',
        'Kėbulo remontas',
        'Diagnostika',
        'Ratų montavimas',
        'Kondicionavimo sistema'
    ];

    return (
        <Layout>
            <Head title="Redaguoti Paslaugą" />
            <div className="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <h1 className="text-2xl font-semibold mb-6">Paslaugos Redagavimas</h1>

                        <form onSubmit={handleSubmit}>
                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="pavadinimas">
                                    Pavadinimas
                                </label>
                                <input
                                    type="text"
                                    id="pavadinimas"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.pavadinimas ? 'border-red-500' : ''
                                        }`}
                                    value={data.pavadinimas}
                                    onChange={e => setData('pavadinimas', e.target.value)}
                                />
                                {errors.pavadinimas && <p className="text-red-500 text-xs italic">{errors.pavadinimas}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="aprasymas">
                                    Aprašymas
                                </label>
                                <textarea
                                    id="aprasymas"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.aprasymas ? 'border-red-500' : ''
                                        }`}
                                    value={data.aprasymas}
                                    onChange={e => setData('aprasymas', e.target.value)}
                                    rows="3"
                                />
                                {errors.aprasymas && <p className="text-red-500 text-xs italic">{errors.aprasymas}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="kategorija">
                                    Kategorija
                                </label>
                                <select
                                    id="kategorija"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.kategorija ? 'border-red-500' : ''
                                        }`}
                                    value={data.kategorija}
                                    onChange={e => setData('kategorija', e.target.value)}
                                >
                                    <option value="">Pasirinkite kategoriją</option>
                                    {kategorijos.map(kategorija => (
                                        <option key={kategorija} value={kategorija}>
                                            {kategorija}
                                        </option>
                                    ))}
                                </select>
                                {errors.kategorija && <p className="text-red-500 text-xs italic">{errors.kategorija}</p>}
                            </div>

                            <div className="mb-4">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="kaina">
                                    Kaina (€)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="kaina"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.kaina ? 'border-red-500' : ''
                                        }`}
                                    value={data.kaina}
                                    onChange={e => setData('kaina', e.target.value)}
                                />
                                {errors.kaina && <p className="text-red-500 text-xs italic">{errors.kaina}</p>}
                            </div>

                            <div className="mb-6">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="trukme_valandomis">
                                    Trukmė (val.)
                                </label>
                                <input
                                    type="number"
                                    step="0.5"
                                    min="0.5"
                                    id="trukme_valandomis"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${errors.trukme_valandomis ? 'border-red-500' : ''
                                        }`}
                                    value={data.trukme_valandomis}
                                    onChange={e => setData('trukme_valandomis', e.target.value)}
                                />
                                {errors.trukme_valandomis && <p className="text-red-500 text-xs italic">{errors.trukme_valandomis}</p>}
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