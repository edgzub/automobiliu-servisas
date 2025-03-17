// resources/js/Components/ServiceCard.jsx
import React from 'react';
import { motion } from 'framer-motion';
import { Link } from '@inertiajs/react';
import { FaClock, FaTag, FaEuroSign } from 'react-icons/fa';

export default function ServiceCard({ service }) {
  // Funkcija, grąžinanti spalvą pagal kategoriją
  const getCategoryColor = (category) => {
    const categories = {
      'Techninė priežiūra': 'from-blue-400 to-blue-600',
      'Variklio remontas': 'from-red-400 to-red-600',
      'Važiuoklės remontas': 'from-yellow-400 to-yellow-600',
      'Stabdžių sistema': 'from-purple-400 to-purple-600',
      'Elektros sistema': 'from-green-400 to-green-600',
      'Kėbulo remontas': 'from-pink-400 to-pink-600',
      'Diagnostika': 'from-indigo-400 to-indigo-600',
      'Ratų montavimas': 'from-gray-400 to-gray-600',
      'Kondicionavimo sistema': 'from-cyan-400 to-cyan-600',
    };
    
    return categories[category] || 'from-gray-400 to-gray-600';
  };

  return (
    <motion.div 
      className="bg-white rounded-lg shadow-md overflow-hidden"
      whileHover={{ y: -5, boxShadow: '0 10px 25px -5px rgba(0, 0, 0, 0.1)' }}
      transition={{ duration: 0.2 }}
    >
      <div className={`h-2 bg-gradient-to-r ${getCategoryColor(service.kategorija)}`} />
      
      <div className="p-5">
        <h3 className="text-lg font-bold text-gray-800 mb-2">{service.pavadinimas}</h3>
        <p className="text-sm text-gray-600 mb-4 line-clamp-2">{service.aprasymas}</p>
        
        <div className="flex justify-between mb-4">
          <div className="flex items-center text-green-700">
            <FaEuroSign className="mr-1" />
            <span className="font-semibold">{parseFloat(service.kaina).toFixed(2)} €</span>
          </div>
          
          <div className="flex items-center text-blue-700">
            <FaClock className="mr-1" />
            <span>{service.trukme_valandomis} val.</span>
          </div>
        </div>
        
        <div className="flex items-center mb-4">
          <FaTag className="mr-1 text-gray-400" />
          <span className="text-xs bg-gray-100 rounded-full px-2 py-1">{service.kategorija}</span>
        </div>
        
        <div className="flex justify-between pt-3 border-t border-gray-100">
          <Link
            href={route('services.edit', service.id)}
            className="text-sm text-blue-600 hover:text-blue-800 font-medium"
          >
            Redaguoti
          </Link>
          
          <Link
            href={route('services.destroy', service.id)}
            method="delete"
            as="button"
            className="text-sm text-red-600 hover:text-red-800 font-medium"
          >
            Ištrinti
          </Link>
        </div>
      </div>
    </motion.div>
  );
}