// resources/js/Components/StatusBadge.jsx
import React from 'react';

export default function StatusBadge({ status }) {
    const colors = {
        laukiama: 'bg-yellow-100 text-yellow-800',
        vykdoma: 'bg-blue-100 text-blue-800',
        atlikta: 'bg-green-100 text-green-800',
        atsaukta: 'bg-red-100 text-red-800',
    };
    
    const text = status.charAt(0).toUpperCase() + status.slice(1);
    
    return (
        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${colors[status] || 'bg-gray-100 text-gray-800'}`}>
            {text}
        </span>
    );
}