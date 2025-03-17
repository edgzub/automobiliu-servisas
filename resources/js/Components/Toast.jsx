// resources/js/Components/Toast.jsx
import { Toaster, toast } from 'react-hot-toast';

export function Toast() {
  return <Toaster position="top-right" toastOptions={{ 
    duration: 3000,
    style: {
      borderRadius: '10px',
      background: '#333',
      color: '#fff',
    },
    success: {
      style: {
        background: '#10B981',
      },
    },
    error: {
      style: {
        background: '#EF4444',
      },
    },
  }} />;
}

export const showToast = {
  success: (message) => toast.success(message),
  error: (message) => toast.error(message),
  loading: (message) => toast.loading(message),
  dismiss: toast.dismiss,
};