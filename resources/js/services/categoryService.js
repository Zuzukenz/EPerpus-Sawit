import { supabase } from '../lib/supabase';

const CATEGORIES_TABLE = 'categories';

export const categoryService = {
  // Get all categories
  async getCategories() {
    try {
      const { data, error } = await supabase
        .from(CATEGORIES_TABLE)
        .select('*');
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching categories:', error);
      return { success: false, error: error.message };
    }
  },

  // Get single category
  async getCategory(id) {
    try {
      const { data, error } = await supabase
        .from(CATEGORIES_TABLE)
        .select('*')
        .eq('id', id)
        .single();
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching category:', error);
      return { success: false, error: error.message };
    }
  },

  // Create category
  async createCategory(categoryData) {
    try {
      const { data, error } = await supabase
        .from(CATEGORIES_TABLE)
        .insert([categoryData])
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error creating category:', error);
      return { success: false, error: error.message };
    }
  },

  // Update category
  async updateCategory(id, categoryData) {
    try {
      const { data, error } = await supabase
        .from(CATEGORIES_TABLE)
        .update(categoryData)
        .eq('id', id)
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error updating category:', error);
      return { success: false, error: error.message };
    }
  },

  // Delete category
  async deleteCategory(id) {
    try {
      const { error } = await supabase
        .from(CATEGORIES_TABLE)
        .delete()
        .eq('id', id);
      
      if (error) throw error;
      return { success: true };
    } catch (error) {
      console.error('Error deleting category:', error);
      return { success: false, error: error.message };
    }
  },
};
