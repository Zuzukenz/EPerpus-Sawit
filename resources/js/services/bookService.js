import { supabase } from '../lib/supabase';

const BOOKS_TABLE = 'books';

export const bookService = {
  // Get all books with category
  async getBooks() {
    try {
      const { data, error } = await supabase
        .from(BOOKS_TABLE)
        .select('*, categories(id, name)');
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching books:', error);
      return { success: false, error: error.message };
    }
  },

  // Get single book
  async getBook(id) {
    try {
      const { data, error } = await supabase
        .from(BOOKS_TABLE)
        .select('*, categories(id, name)')
        .eq('id', id)
        .single();
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching book:', error);
      return { success: false, error: error.message };
    }
  },

  // Create book
  async createBook(bookData) {
    try {
      const { data, error } = await supabase
        .from(BOOKS_TABLE)
        .insert([bookData])
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error creating book:', error);
      return { success: false, error: error.message };
    }
  },

  // Update book
  async updateBook(id, bookData) {
    try {
      const { data, error } = await supabase
        .from(BOOKS_TABLE)
        .update(bookData)
        .eq('id', id)
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error updating book:', error);
      return { success: false, error: error.message };
    }
  },

  // Delete book
  async deleteBook(id) {
    try {
      const { error } = await supabase
        .from(BOOKS_TABLE)
        .delete()
        .eq('id', id);
      
      if (error) throw error;
      return { success: true };
    } catch (error) {
      console.error('Error deleting book:', error);
      return { success: false, error: error.message };
    }
  },

  // Search books
  async searchBooks(query) {
    try {
      const { data, error } = await supabase
        .from(BOOKS_TABLE)
        .select('*, categories(id, name)')
        .or(`title.ilike.%${query}%,author.ilike.%${query}%`);
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error searching books:', error);
      return { success: false, error: error.message };
    }
  },

  // Get low stock books
  async getLowStockBooks(threshold = 5) {
    try {
      const { data, error } = await supabase
        .from(BOOKS_TABLE)
        .select('*')
        .lt('quantity', threshold);
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching low stock books:', error);
      return { success: false, error: error.message };
    }
  },
};
