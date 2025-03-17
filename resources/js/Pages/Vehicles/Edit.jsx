import React, { useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import axios from 'axios';
import Layout from '../Layout';

export default function Edit({ vehicle, clients }) {
    const { data, setData, put, processing, errors } = useForm({
        client_id: vehicle.client_id || '',
        marke: vehicle.marke || '',
        modelis: vehicle.modelis || '',
        metai: vehicle.metai || new Date().getFullYear(),
        valstybinis_numeris: vehicle.valstybinis_numeris || '',
        vin_kodas: vehicle.vin_kodas || '',
    });

    // Paieškos kintamieji
    const [searchTerm, setSearchTerm] = useState(vehicle.marke || '');
    const [filteredMakes, setFilteredMakes] = useState([]);
    const [allMakes, setAllMakes] = useState([]);

    // Būsenų kintamieji API duomenims
    const [models, setModels] = useState([]);
    const [years, setYears] = useState([]);
    const [loading, setLoading] = useState({
        makes: false,
        models: false,
        years: false
    });

    // Gauname markes, kai puslapis užkraunamas
    useEffect(() => {
        const fetchAllMakes = async () => {
            try {
                const response = await axios.get('/api/v1/cars/makes');
                setAllMakes(response.data);
            } catch (error) {
                console.error("Klaida gaunant gamintojus:", error);
            }
        };

        fetchAllMakes();
    }, []);

    // Filtruoti pagal įvestą paieškos tekstą
    useEffect(() => {
        if (searchTerm.trim() === '') {
            setFilteredMakes([]); // Tuščias sąrašas, kai nieko neįvesta
        } else {
            const filtered = allMakes.filter(make => 
                make.Make_Name.toLowerCase().includes(searchTerm.toLowerCase())
            ).slice(0, 10); // Rodomi tik pirmi 10 rezultatų
            setFilteredMakes(filtered);
        }
    }, [searchTerm, allMakes]);

    // Gauname metus
    useEffect(() => {
        setLoading(prev => ({ ...prev, years: true }));
        axios.get('/api/v1/cars/years')
            .then(response => {
                setYears(response.data);
            })
            .catch(error => {
                console.error('Klaida gaunant metus:', error);
            })
            .finally(() => {
                setLoading(prev => ({ ...prev, years: false }));
            });
    }, []);

    // Gauname modelius, kai pasikeičia markė
    useEffect(() => {
        if (data.marke) {
            setLoading(prev => ({ ...prev, models: true }));
            axios.get(`/api/v1/cars/models/${data.marke}`)
                .then(response => {
                    setModels(response.data);
                })
                .catch(error => {
                    console.error('Klaida gaunant automobilių modelius:', error);
                })
                .finally(() => {
                    setLoading(prev => ({ ...prev, models: false }));
                });
        } else {
            setModels([]);
        }
    }, [data.marke]);

    // VIN dekodavimo funkcija
    const decodeVin = (vin) => {
        if (vin.length === 17) { // VIN kodas turi būti 17 simbolių
            axios.get(`/api/v1/cars/decode-vin/${vin}`)
                .then(response => {
                    if (response.data.marke) {
                        setData({
                            ...data,
                            marke: response.data.marke.toLowerCase(),
                            modelis: response.data.modelis,
                            metai: response.data.metai || data.metai
                        });
                    }
                })
                .catch(error => {
                    console.error('Klaida dekoduojant VIN:', error);
                });
        }
    };

    function handleSubmit(e) {
        e.preventDefault();
        put(route('vehicles.update', vehicle.id));
    }

    return (
        <Layout>
            <Head title="Redaguoti Automobilį" />
            <div className="max-w-3xl mx-auto py-8 sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <h1 className="text-2xl font-semibold mb-6">Automobilio Redagavimas</h1>

                        <form onSubmit={handleSubmit}>
                            <div className="mb-6">
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

                            <div className="mb-6">
                                <label htmlFor="make" className="block text-gray-700 text-sm font-bold mb-2">
                                    Gamintojas
                                </label>
                                <input 
                                    type="text"
                                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="make"
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                    placeholder="Įveskite automobilio gamintoją"
                                />
                                
                                {filteredMakes.length > 0 && (
                                    <div className="search-results mt-1 absolute z-10 bg-white border border-gray-300 rounded shadow-lg w-full max-w-3xl">
                                        {filteredMakes.map(make => (
                                            <div 
                                                key={make.Make_ID} 
                                                className="search-item px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                                onClick={() => {
                                                    setSearchTerm(make.Make_Name);
                                                    setData('marke', make.Make_Name);
                                                    setFilteredMakes([]);
                                                }}
                                            >
                                                {make.Make_Name}
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </div>

                            <div className="mb-6">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="modelis">
                                    Modelis
                                </label>
                                <select
                                    id="modelis"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.modelis ? 'border-red-500' : ''
                                    }`}
                                    value={data.modelis}
                                    onChange={e => setData('modelis', e.target.value)}
                                    disabled={loading.models || !data.marke}
                                >
                                    <option value="">Pasirinkite modelį</option>
                                    {models.map((model, index) => (
                                        <option key={model.Model_ID || index} value={model.Model_Name}>
                                            {model.Model_Name}
                                        </option>
                                    ))}
                                </select>
                                {loading.models && <p className="text-gray-500 text-xs">Kraunamas modelių sąrašas...</p>}
                                {errors.modelis && <p className="text-red-500 text-xs italic">{errors.modelis}</p>}
                            </div>

                            <div className="mb-6">
                                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="metai">
                                    Metai
                                </label>
                                <select
                                    id="metai"
                                    className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                        errors.metai ? 'border-red-500' : ''
                                    }`}
                                    value={data.metai}
                                    onChange={e => setData('metai', e.target.value)}
                                    disabled={loading.years}
                                >
                                    <option value="">Pasirinkite metus</option>
                                    {years.map(year => (
                                        <option key={year.year} value={year.year}>
                                            {year.year}
                                        </option>
                                    ))}
                                </select>
                                {loading.years && <p className="text-gray-500 text-xs">Kraunami metai...</p>}
                                {errors.metai && <p className="text-red-500 text-xs italic">{errors.metai}</p>}
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
                                    onChange={e => {
                                        const vin = e.target.value.toUpperCase();
                                        setData('vin_kodas', vin);
                                        decodeVin(vin);
                                    }}
                                />
                                {errors.vin_kodas && <p className="text-red-500 text-xs italic">{errors.vin_kodas}</p>}
                            </div>

                            <div className="mb-6">
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