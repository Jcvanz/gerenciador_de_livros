import React, { useState } from 'react';
import useSWR, { mutate } from 'swr';
import {
  Container, Table, TableBody, TableCell, TableContainer, TableHead,
  TableRow, Paper, Button, Typography, Alert, CircularProgress,
  Box, Pagination
} from '@mui/material';

const fetcher = (url) => fetch(url, {
  headers: { 'Accept': 'application/json' }
}).then(res => {
  if (!res.ok) throw new Error('Falha ao obter livros.');
  return res.json();
});

export default function LivrosListPage() {
  const [page, setPage] = useState(1);
  const [feedback, setFeedback] = useState({ text: '', type: 'info' });

  const { data: listagem, error, isLoading } = useSWR(`/api/livros?page=${page}`, fetcher);

  const handleEmprestar = async (livroId) => {
    setFeedback({ text: '', type: 'info' });
    try {
      const res = await fetch('/api/emprestar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ livro_id: livroId })
      });

      if (!res.ok) {
        throw new Error('Erro ao emprestar livro');
      }

      setFeedback({ text: 'Livro emprestado', type: 'success' });
      
      mutate(`/api/livros?page=${page}`);
    } catch {
      setFeedback({ text: 'Erro ao emprestar livro', type: 'error' });
    }
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
        <Alert severity="error">Erro ao exibir lista de livros</Alert>
      </Container>
    );
  }

  return (
    <Container maxWidth="lg" sx={{ mt: 6, mb: 6 }}>
      <Box sx={{ textAlign: 'center', mb: 4 }}>
        <Typography variant="h4" component="h1" gutterBottom sx={{ fontWeight: 'bold' }}>
          Catálogo de Livros
        </Typography>
      </Box>

      {feedback.text && (
        <Alert severity={feedback.type} sx={{ mb: 3 }}>
          {feedback.text}
        </Alert>
      )}

      <TableContainer component={Paper} sx={{ overflowX: 'auto', borderRadius: 2, width: '100%', maxWidth: '100%' }}>
        <Table sx={{ minWidth: 800 }}>
          <TableHead sx={{ backgroundColor: 'background.default' }}>
            <TableRow>
              <TableCell sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Título</TableCell>
              <TableCell sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Autor</TableCell>
              <TableCell sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>ISBN</TableCell>
              <TableCell align="center" sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Cópias Disponíveis</TableCell>
              <TableCell align="center" sx={{ fontWeight: 'bold', whiteSpace: 'nowrap' }}>Ações</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {(Array.isArray(listagem) ? listagem : listagem?.data)?.map((livro) => (
              <TableRow key={livro.id} hover>
                <TableCell sx={{ whiteSpace: 'nowrap' }}>{livro.titulo}</TableCell>
                <TableCell sx={{ whiteSpace: 'nowrap' }}>{livro.autor}</TableCell>
                <TableCell sx={{ whiteSpace: 'nowrap' }}>{livro.isbn}</TableCell>
                <TableCell align="center" sx={{ whiteSpace: 'nowrap' }}>{livro.quantidade_copias}</TableCell>
                <TableCell align="center" sx={{ whiteSpace: 'nowrap' }}>
                  <Button
                    variant="contained"
                    color="primary"
                    disabled={livro.quantidade_copias <= 0}
                    onClick={() => handleEmprestar(livro.id)}
                    sx={{ textTransform: 'none', borderRadius: 2 }}
                  >
                    Emprestar
                  </Button>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>

      {/* Controles de Paginação (exibido apenas se vier paginado do backend) */}
      {listagem?.last_page > 1 && (
        <Box sx={{ display: 'flex', justifyContent: 'center', mt: 4 }}>
          <Pagination
            count={listagem.last_page}
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
