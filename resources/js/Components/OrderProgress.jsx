// resources/js/Components/OrderProgress.jsx
import React from 'react';

export default function OrderProgress({ status }) {
    const steps = [
        { id: 'naujas', name: 'Naujas', color: 'bg-blue-500' },
        { id: 'vykdomas', name: 'Vykdomas', color: 'bg-yellow-500' },
        { id: 'baigtas', name: 'Baigtas', color: 'bg-green-500' }
    ];
    
    // Surandame dabartinio žingsnio indeksą
    const currentStepIndex = steps.findIndex(step => step.id.toLowerCase() === status.toLowerCase());
    const isCancelled = status.toLowerCase() === 'atšauktas';
    
    // Apskaičiuojame progreso procentą
    const progressPercent = isCancelled 
        ? 100 
        : currentStepIndex >= 0 
            ? ((currentStepIndex + 1) / steps.length) * 100
            : 0;
    
    return (
        <div className="w-full py-2">
            <div className="relative">
                {/* Progreso juosta */}
                <div className="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                    <div 
                        style={{ width: `${progressPercent}%` }}
                        className={`shadow-none flex flex-col justify-center text-center text-white transition-all duration-500 ease-in-out
                                   ${isCancelled ? 'bg-red-500' : currentStepIndex >= 0 ? steps[currentStepIndex].color : ''}`}
                    ></div>
                </div>
                
                {/* Žingsniai */}
                <div className="flex justify-between">
                    {steps.map((step, index) => (
                        <div key={step.id} className="text-center">
                            <div 
                                className={`
                                    w-6 h-6 mb-1 rounded-full flex items-center justify-center transition-all duration-300
                                    ${index <= currentStepIndex && !isCancelled ? step.color : 'bg-gray-300'}
                                    ${isCancelled ? 'bg-red-500' : ''}
                                    ${step.id.toLowerCase() === status.toLowerCase() ? 'ring-4 ring-opacity-50 ring-gray-300' : ''}
                                `}
                            >
                                {index < currentStepIndex || (currentStepIndex === steps.length - 1 && index === currentStepIndex) ? (
                                    <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                ) : (
                                    <span className="text-white text-xs font-bold">{index + 1}</span>
                                )}
                            </div>
                            <span className="text-xs font-medium text-gray-500 mt-1">{step.name}</span>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}