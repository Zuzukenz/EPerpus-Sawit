import React, { useState } from 'react';
import { useCategories } from '../hooks/useCategories';
import { categoryService } from '../services/categoryService';
import './CategoriesList.css';

function CategoriesList() {
  const { categories, loading, error, refetch } = useCategories();
  const [showForm, setShowForm] = useState(false);
  const [formData, setFormData] = useState({ name: '', description: '' });

  const handleSubmit = async (e) => {
    e.preventDefault();
    const result = await categoryService.createCategory(formData);
    if (result.success) {
      setFormData({ name: '', description: '' });
      setShowForm(false);
      refetch();
    }
  };

  const handleDelete = async (id) => {
    if (confirm('Yakin ingin menghapus kategori ini?')) {
      const result = await categoryService.deleteCategory(id);
      if (result.success) {
        refetch();
      }
    }
  };

  if (loading) return <div className="loading">Loading...</div>;
  if (error) return <div className="error">Error: {error}</div>;

  return (
    <div className="categories-list">
      <div className="header">
        <h2>Daftar Kategori</h2>
        <button className="btn-primary" onClick={() => setShowForm(!showForm)}>
          {showForm ? '✕ Tutup' : '+ Tambah Kategori'}
        </button>
      </div>

      {showForm && (
        <form className="category-form" onSubmit={handleSubmit}>
          <input
            type="text"
            placeholder="Nama Kategori"
            required
            value={formData.name}
            onChange={(e) => setFormData({ ...formData, name: e.target.value })}
          />
          <textarea
            placeholder="Deskripsi"
            value={formData.description}
            onChange={(e) => setFormData({ ...formData, description: e.target.value })}
          />
          <button type="submit" className="btn-primary">Simpan</button>
        </form>
      )}

      <div className="categories-grid">
        {categories.map((category) => (
          <div key={category.id} className="category-card">
            <h3>{category.name}</h3>
            <p>{category.description}</p>
            <button className="btn-danger" onClick={() => handleDelete(category.id)}>Hapus</button>
          </div>
        ))}
      </div>
    </div>
  );
}

export default CategoriesList;
