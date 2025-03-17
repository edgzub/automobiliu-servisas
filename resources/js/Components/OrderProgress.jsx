import React from 'react';

export default function OrderProgress({ status }) {
    // Statuso procentai
    let percentage = 0;
    
    switch (status) {
        case 'laukiama':
            percentage = 25;
            break;
        case 'vykdoma':
            percentage = 65;
            break;
        case 'atlikta':
            percentage = 100;
            break;
        case 'atsaukta':
            percentage = 0;
            break;
        default:
            percentage = 0;
    }
    
    return (
        <div className="w-full">
            <div className="relative h-2 bg-gray-200 rounded overflow-hidden">
                <div 
                    className={`absolute h-full left-0 top-0 ${status === 'atsaukta' ? 'bg-red-500' : 'bg-blue-500'}`}
                    style={{ width: `${percentage}%` }}
                ></div>
            </div>
        </div>
    );
}