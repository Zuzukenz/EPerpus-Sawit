import { supabase } from '../lib/supabase';

const BORROWINGS_TABLE = 'borrowings';

export const borrowingService = {
  // Get all borrowings
  async getBorrowings() {
    try {
      const { data, error } = await supabase
        .from(BORROWINGS_TABLE)
        .select('*, members(*), books(*)');
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching borrowings:', error);
      return { success: false, error: error.message };
    }
  },

  // Get single borrowing
  async getBorrowing(id) {
    try {
      const { data, error } = await supabase
        .from(BORROWINGS_TABLE)
        .select('*, members(*), books(*)')
        .eq('id', id)
        .single();
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching borrowing:', error);
      return { success: false, error: error.message };
    }
  },

  // Get active borrowings
  async getActiveBorrowings() {
    try {
      const { data, error } = await supabase
        .from(BORROWINGS_TABLE)
        .select('*, members(*), books(*)')
        .eq('status', 'borrowed');
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching active borrowings:', error);
      return { success: false, error: error.message };
    }
  },

  // Get overdue borrowings
  async getOverdueBorrowings() {
    try {
      const today = new Date().toISOString().split('T')[0];
      const { data, error } = await supabase
        .from(BORROWINGS_TABLE)
        .select('*, members(*), books(*)')
        .eq('status', 'borrowed')
        .lt('return_date', today);
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching overdue borrowings:', error);
      return { success: false, error: error.message };
    }
  },

  // Create borrowing
  async createBorrowing(borrowingData) {
    try {
      const { data, error } = await supabase
        .from(BORROWINGS_TABLE)
        .insert([borrowingData])
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error creating borrowing:', error);
      return { success: false, error: error.message };
    }
  },

  // Update borrowing
  async updateBorrowing(id, borrowingData) {
    try {
      const { data, error } = await supabase
        .from(BORROWINGS_TABLE)
        .update(borrowingData)
        .eq('id', id)
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error updating borrowing:', error);
      return { success: false, error: error.message };
    }
  },

  // Return book (update status to returned)
  async returnBook(id) {
    try {
      const { data, error } = await supabase
        .from(BORROWINGS_TABLE)
        .update({ 
          status: 'returned',
          actual_return_date: new Date().toISOString()
        })
        .eq('id', id)
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error returning book:', error);
      return { success: false, error: error.message };
    }
  },

  // Delete borrowing
  async deleteBorrowing(id) {
    try {
      const { error } = await supabase
        .from(BORROWINGS_TABLE)
        .delete()
        .eq('id', id);
      
      if (error) throw error;
      return { success: true };
    } catch (error) {
      console.error('Error deleting borrowing:', error);
      return { success: false, error: error.message };
    }
  },

  // Get member borrowing history
  async getMemberBorrowings(memberId) {
    try {
      const { data, error } = await supabase
        .from(BORROWINGS_TABLE)
        .select('*, books(*)')
        .eq('member_id', memberId);
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching member borrowings:', error);
      return { success: false, error: error.message };
    }
  },
};
