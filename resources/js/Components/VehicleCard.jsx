// resources/js/Components/VehicleCard.jsx
import React from 'react';
import { motion } from 'framer-motion';
import { Link } from '@inertiajs/react';
import { FaCar, FaCalendarAlt, FaHashtag, FaUser } from 'react-icons/fa';

export default function VehicleCard({ vehicle }) {
  return (
    <motion.div 
      className="bg-white rounded-lg shadow-md overflow-hidden"
      whileHover={{ y: -5, boxShadow: '0 10px 25px -5px rgba(0, 0, 0, 0.1)' }}
      transition={{ duration: 0.2 }}
    >
      <div className="h-2 bg-gradient-to-r from-blue-400 to-blue-600" />
      
      <div className="p-5">
        <div className="flex justify-between items-center mb-3">
          <h3 className="text-lg font-bold text-gray-800">
            {vehicle.marke} {vehicle.modelis}
          </h3>
          <span className="text-xl text-blue-500">
            <FaCar />
          </span>
        </div>
        
        <div className="space-y-2 mb-4">
          <div className="flex items-center text-sm text-gray-600">
            <FaCalendarAlt className="mr-2 text-gray-400" />
            <span>{vehicle.metai}</span>
          </div>
          
          <div className="flex items-center text-sm text-gray-600">
            <FaHashtag className="mr-2 text-gray-400" />
            <span>{vehicle.valstybinis_numeris}</span>
          </div>
          
          <div className="flex items-center text-sm text-gray-600">
            <FaUser className="mr-2 text-gray-400" />
            <span>{vehicle.client?.vardas} {vehicle.client?.pavarde}</span>
          </div>
        </div>
        
        <div className="flex justify-between pt-3 border-t border-gray-100">
          <Link
            href={route('vehicles.edit', vehicle.id)}
            className="text-sm text-blue-600 hover:text-blue-800 font-medium"
          >
            Redaguoti
          </Link>
          
          <Link
            href={route('vehicles.destroy', vehicle.id)}
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