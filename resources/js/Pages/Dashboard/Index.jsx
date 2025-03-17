import React, { useState, useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Layout';

export default function Index() {
    const [animate, setAnimate] = useState(false);
    
    useEffect(() => {
        // Paleiskime animaciją kai puslapis užkraunamas
        setAnimate(true);
    }, []);
    
    const cards = [
        { title: "Klientai", desc: "Valdykite klientų informaciją", color: "bg-gradient-to-r from-blue-400 to-blue-600", link: "/clients" },
        { title: "Automobiliai", desc: "Tvarkykite automobilių duomenis", color: "bg-gradient-to-r from-green-400 to-green-600", link: "/vehicles" },
        { title: "Paslaugos", desc: "Administruokite teikiamas paslaugas", color: "bg-gradient-to-r from-yellow-400 to-yellow-600", link: "/services" },
        { title: "Užsakymai", desc: "Sekite užsakymų eigą", color: "bg-gradient-to-r from-purple-400 to-purple-600", link: "/orders" }
    ];

    return (
        <Layout>
            <Head title="Pagrindinis" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div 
                        className={`bg-white overflow-hidden shadow-lg sm:rounded-lg transition-opacity duration-1000 ${animate ? 'opacity-100' : 'opacity-0'}`}
                    >
                        <div className="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <h1 className="text-3xl font-bold mb-8 text-center text-gray-800">
                                Automobilių Serviso Valdymo Sistema
                            </h1>
                            
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                {cards.map((card, index) => (
                                    <div
                                        key={index}
                                        className={`${card.color} p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 
                                        transform hover:-translate-y-1 ${animate ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}
                                        style={{ transitionDelay: `${index * 100}ms` }}
                                    >
                                        <Link href={card.link} className="block">
                                            <h2 className="text-xl font-semibold mb-2 text-white">{card.title}</h2>
                                            <p className="text-white opacity-80">{card.desc}</p>
                                        </Link>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}