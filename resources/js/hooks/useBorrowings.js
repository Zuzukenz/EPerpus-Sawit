import { useState, useEffect } from 'react';
import { borrowingService } from '../services/borrowingService';

export function useBorrowings() {
  const [borrowings, setBorrowings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchBorrowings = async () => {
    setLoading(true);
    const result = await borrowingService.getBorrowings();
    if (result.success) {
      setBorrowings(result.data);
      setError(null);
    } else {
      setError(result.error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchBorrowings();
  }, []);

  return { borrowings, loading, error, refetch: fetchBorrowings };
}

export function useOverdueBorrowings() {
  const [overdue, setOverdue] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchOverdue = async () => {
    setLoading(true);
    const result = await borrowingService.getOverdueBorrowings();
    if (result.success) {
      setOverdue(result.data);
      setError(null);
    } else {
      setError(result.error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchOverdue();
  }, []);

  return { overdue, loading, error, refetch: fetchOverdue };
}
