import React, { useState } from 'react';
import { useMembers } from '../hooks/useMembers';
import { memberService } from '../services/memberService';
import './MembersList.css';

function MembersList() {
  const { members, loading, error, refetch } = useMembers();
  const [searchQuery, setSearchQuery] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    address: '',
    membership_date: new Date().toISOString().split('T')[0],
  });

  const handleSearch = async (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      const result = await memberService.searchMembers(searchQuery);
      if (result.success) {
        setMembers(result.data);
      }
    } else {
      refetch();
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const result = await memberService.createMember(formData);
    if (result.success) {
      setFormData({ name: '', email: '', phone: '', address: '', membership_date: new Date().toISOString().split('T')[0] });
      setShowForm(false);
      refetch();
    }
  };

  const handleDelete = async (id) => {
    if (confirm('Yakin ingin menghapus member ini?')) {
      const result = await memberService.deleteMember(id);
      if (result.success) {
        refetch();
      }
    }
  };

  if (loading) return <div className="loading">Loading...</div>;
  if (error) return <div className="error">Error: {error}</div>;

  return (
    <div className="members-list">
      <div className="header">
        <h2>Daftar Member</h2>
        <button className="btn-primary" onClick={() => setShowForm(!showForm)}>
          {showForm ? '✕ Tutup' : '+ Tambah Member'}
        </button>
      </div>

      {showForm && (
        <form className="member-form" onSubmit={handleSubmit}>
          <input
            type="text"
            placeholder="Nama"
            required
            value={formData.name}
            onChange={(e) => setFormData({ ...formData, name: e.target.value })}
          />
          <input
            type="email"
            placeholder="Email"
            required
            value={formData.email}
            onChange={(e) => setFormData({ ...formData, email: e.target.value })}
          />
          <input
            type="tel"
            placeholder="Telepon"
            value={formData.phone}
            onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
          />
          <input
            type="text"
            placeholder="Alamat"
            value={formData.address}
            onChange={(e) => setFormData({ ...formData, address: e.target.value })}
          />
          <input
            type="date"
            value={formData.membership_date}
            onChange={(e) => setFormData({ ...formData, membership_date: e.target.value })}
          />
          <button type="submit" className="btn-primary">Simpan</button>
        </form>
      )}

      <form className="search-form" onSubmit={handleSearch}>
        <input
          type="text"
          placeholder="Cari member..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
        />
        <button type="submit" className="btn-secondary">Cari</button>
      </form>

      <div className="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Telepon</th>
              <th>Alamat</th>
              <th>Terdaftar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            {members.map((member) => (
              <tr key={member.id}>
                <td>{member.id}</td>
                <td>{member.name}</td>
                <td>{member.email}</td>
                <td>{member.phone}</td>
                <td>{member.address}</td>
                <td>{new Date(member.membership_date).toLocaleDateString('id-ID')}</td>
                <td>
                  <button className="btn-danger" onClick={() => handleDelete(member.id)}>Hapus</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export default MembersList;
