import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './hooks/useAuth';
import Layout from './components/Layout';
import Dashboard from './pages/Dashboard';
import BooksPage from './pages/BooksPage';
import CategoriesPage from './pages/CategoriesPage';
import MembersPage from './pages/MembersPage';
import BorrowingsPage from './pages/BorrowingsPage';
import './App.css';

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Layout />}>
            <Route index element={<Dashboard />} />
            <Route path="books" element={<BooksPage />} />
            <Route path="categories" element={<CategoriesPage />} />
            <Route path="members" element={<MembersPage />} />
            <Route path="borrowings" element={<BorrowingsPage />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;
