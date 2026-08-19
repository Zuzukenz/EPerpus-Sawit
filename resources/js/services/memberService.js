import { supabase } from '../lib/supabase';

const MEMBERS_TABLE = 'members';

export const memberService = {
  // Get all members
  async getMembers() {
    try {
      const { data, error } = await supabase
        .from(MEMBERS_TABLE)
        .select('*');
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching members:', error);
      return { success: false, error: error.message };
    }
  },

  // Get single member with borrowing history
  async getMember(id) {
    try {
      const { data, error } = await supabase
        .from(MEMBERS_TABLE)
        .select('*, borrowings(*)')
        .eq('id', id)
        .single();
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error fetching member:', error);
      return { success: false, error: error.message };
    }
  },

  // Create member
  async createMember(memberData) {
    try {
      const { data, error } = await supabase
        .from(MEMBERS_TABLE)
        .insert([memberData])
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error creating member:', error);
      return { success: false, error: error.message };
    }
  },

  // Update member
  async updateMember(id, memberData) {
    try {
      const { data, error } = await supabase
        .from(MEMBERS_TABLE)
        .update(memberData)
        .eq('id', id)
        .select();
      
      if (error) throw error;
      return { success: true, data: data[0] };
    } catch (error) {
      console.error('Error updating member:', error);
      return { success: false, error: error.message };
    }
  },

  // Delete member
  async deleteMember(id) {
    try {
      const { error } = await supabase
        .from(MEMBERS_TABLE)
        .delete()
        .eq('id', id);
      
      if (error) throw error;
      return { success: true };
    } catch (error) {
      console.error('Error deleting member:', error);
      return { success: false, error: error.message };
    }
  },

  // Search members
  async searchMembers(query) {
    try {
      const { data, error } = await supabase
        .from(MEMBERS_TABLE)
        .select('*')
        .or(`name.ilike.%${query}%,email.ilike.%${query}%`);
      
      if (error) throw error;
      return { success: true, data };
    } catch (error) {
      console.error('Error searching members:', error);
      return { success: false, error: error.message };
    }
  },
};
