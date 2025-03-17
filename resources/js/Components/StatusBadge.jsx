// resources/js/Components/StatusBadge.jsx
import React from 'react';

export default function StatusBadge({ status }) {
    const getStatusConfig = (status) => {
        switch(status.toLowerCase()) {
            case 'naujas':
                return { bg: 'bg-blue-100', text: 'text-blue-800', border: 'border-blue-200', emoji: '🆕' };
            case 'vykdomas':
                return { bg: 'bg-yellow-100', text: 'text-yellow-800', border: 'border-yellow-200', emoji: '⚙️' };
            case 'baigtas':
                return { bg: 'bg-green-100', text: 'text-green-800', border: 'border-green-200', emoji: '✅' };
            case 'atšauktas':
                return { bg: 'bg-red-100', text: 'text-red-800', border: 'border-red-200', emoji: '❌' };
            default:
                return { bg: 'bg-gray-100', text: 'text-gray-800', border: 'border-gray-200', emoji: '❓' };
        }
    };
    
    const config = getStatusConfig(status);
    
    return (
        <span 
            className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border ${config.bg} ${config.text} ${config.border} transition-all duration-300 hover:scale-105`}
        >
            <span className="mr-1">{config.emoji}</span>
            {status.charAt(0).toUpperCase() + status.slice(1)}
        </span>
    );
}