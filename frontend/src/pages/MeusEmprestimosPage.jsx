import React, { useState } from 'react';
import useSWR, { mutate } from 'swr';
import {
  Container, 
  Table, 
  TableBody, 
  TableCell, 
  TableContainer, 
  TableHead,
  TableRow, 
  Paper, 
  Button, 
  Typography, 
  Alert, 
  CircularProgress, 
  Box, 
  Chip,
  Pagination
} from '@mui/material';

const fetcher = (url) => fetch(url, {
  headers: { 
    'Accept': 'application/json' 
  }})
  .then(res => {
    if (!res.ok) {
      throw new Error('Não foi possível obter a lista de empréstimos');
    }
    return res.json();
});

export default function MeusEmprestimosPage() {
  const [page, setPage] = useState(1);
  const [feedback, setFeedback] = useState({ text: '', type: 'info' });

  const { data: emprestimos, error, isLoading } = useSWR(`/api/meus-emprestimos?page=${page}`, fetcher);

  const handleDevolver = async (emprestimoId) => {
    setFeedback({ text: '', type: 'info' });
    
    try {
      const res = await fetch(`/api/devolver/${emprestimoId}`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      });

      if (!res.ok) {
        throw new Error('Erro ao devolver livro');
      }

      setFeedback({ text: 'Livro devolvido', type: 'success' });

      mutate(`/api/meus-emprestimos?page=${page}`);
    } catch {
      setFeedback({ text: 'Erro ao devolver livro', type: 'error' });
    }
  };

  const getStatusChip = (status) => {
    if (status === 'devolvido') {
      return <Chip label="Devolvido" color="success" size="small" />;
    }

    if (status === 'atrasado') {
      return <Chip label="Atrasado" color="error" size="small" />;
    }

    return <Chip label="Ativo" color="primary" size="small" />;
  };

  if (isLoading) {
    return (
      <Box display="flex" sx={{ mt: 8, justifyContent: 'center' }}>
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return (
      <Container sx={{ mt: 4 }}>
        <Alert severity="error">Erro ao obter lista de empréstimos</Alert>
      </Container>
    );
  }

  return (
    <Container maxWidth="lg" sx={{ mt: 6, mb: 6 }}>
      <Box sx={{ textAlign: 'center', mb: 4 }}>
        <Typography variant="h4" component="h1" gutterBottom sx={{ fontWeight: 'bold' }}>
          Meus Livros Alugados
        </Typography>
      </Box>

      {feedback.text && (
        <Alert severity={feedback.type} sx={{ mb: 3 }}>
          {feedback.text}
        </Alert>
      )}

      <TableContainer component={Paper} elevation={2} sx={{ overflowX: 'auto', borderRadius: 2, width: '100%', maxWidth: '100%' }}>
        <Table sx={{ minWidth: 800 }}>
          <TableHead sx={{ backgroundColor: 'background.default' }}>
            <TableRow>
              <TableCell sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Livro</TableCell>
              <TableCell sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Autor</TableCell>
              <TableCell sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>ISBN</TableCell>
              <TableCell align="center" sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Retirado em</TableCell>
              <TableCell align="center" sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Devolvido em</TableCell>
              <TableCell align="center" sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Status</TableCell>
              <TableCell align="center" sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Ações</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {(Array.isArray(emprestimos) ? emprestimos : emprestimos?.data)?.map((item) => (
              <TableRow key={item.id} hover>
                <TableCell sx={{ whiteSpace: 'nowrap' }}>{item.titulo}</TableCell>
                <TableCell sx={{ whiteSpace: 'nowrap' }}>{item.autor}</TableCell>
                <TableCell sx={{ whiteSpace: 'nowrap' }}>{item.isbn}</TableCell>
                <TableCell align="center" sx={{ whiteSpace: 'nowrap' }}>{new Date(item.data_emprestimo).toLocaleDateString('pt-BR')}</TableCell>
                <TableCell align="center" sx={{ whiteSpace: 'nowrap' }}>
                  {item.data_devolucao 
                    ? new Date(item.data_devolucao).toLocaleDateString('pt-BR') 
                    : 'Pendente'}
                </TableCell>
                <TableCell align="center" sx={{ whiteSpace: 'nowrap' }}>
                  {getStatusChip(item.status)}
                </TableCell>
                <TableCell align="center" sx={{ whiteSpace: 'nowrap' }}>
                  {item.status === 'ativo' && (
                    <Button
                      variant="outlined"
                      color="secondary"
                      onClick={() => handleDevolver(item.id)}
                      sx={{ textTransform: 'none', borderRadius: 2 }}
                    >
                      Devolver
                    </Button>
                  )}
                </TableCell>
              </TableRow>
            ))}
            {(!emprestimos || (Array.isArray(emprestimos) ? emprestimos.length === 0 : !emprestimos.data || emprestimos.data.length === 0)) && (
              <TableRow>
                <TableCell colSpan={7} align="center" sx={{ whiteSpace: 'nowrap' }}>
                  Nenhum registro de empréstimo encontrado
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </TableContainer>

      {emprestimos?.last_page > 1 && (
        <Box sx={{ display: 'flex', justifyContent: 'center', mt: 4 }}>
          <Pagination
            count={emprestimos.last_page}
            page={page}
            onChange={(e, val) => setPage(val)}
            color="primary"
            size="large"
          />
        </Box>
      )}
    </Container>
  );
}
