import React, { useContext, useState } from 'react';
import { Link, useNavigate, Outlet } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';

import {
  Box, 
  CssBaseline, 
  AppBar, 
  Toolbar, 
  Typography, 
  Drawer, 
  List,
  ListItem, 
  ListItemButton, 
  ListItemText, 
  Button, 
  Divider,
  IconButton,
  ListItemIcon
} from '@mui/material';
import { UserCircle, Menu, BookOpen, Bookmark, LogOut } from 'lucide-react';

export default function SidebarLayout() {
  const { user, logout } = useContext(AuthContext);
  const navigate = useNavigate();
  const [mobileOpen, setMobileOpen] = useState(false);

  const handleDrawerToggle = () => {
    setMobileOpen(!mobileOpen);
  };

  const handleLogoutClick = async () => {
    setMobileOpen(false);
    await logout();
    navigate('/login');
  };

  const drawerContent = (
    <Box sx={{ height: '100%', display: 'flex', flexDirection: 'column' }}>
      <Toolbar />
      <Divider />
      <Box sx={{ overflow: 'auto', flexGrow: 1 }}>
        <List>
          <ListItem disablePadding>
            <ListItemButton component={Link} to="/livros" onClick={() => setMobileOpen(false)}>
              <ListItemIcon sx={{ minWidth: 40 }}>
                <BookOpen size={20} color="#38BDF8" />
              </ListItemIcon>
              <ListItemText primary="Livros Disponíveis" />
            </ListItemButton>
          </ListItem>
          <ListItem disablePadding>
            <ListItemButton component={Link} to="/meus-emprestimos" onClick={() => setMobileOpen(false)}>
              <ListItemIcon sx={{ minWidth: 40 }}>
                <Bookmark size={20} color="#38BDF8" />
              </ListItemIcon>
              <ListItemText primary="Meus Empréstimos" />
            </ListItemButton>
          </ListItem>
        </List>
      </Box>
      <Divider />
      <Box sx={{ p: 2 }}>
        <Button 
          variant="outlined" 
          color="error" 
          fullWidth 
          onClick={handleLogoutClick}
          startIcon={<LogOut size={16} />}
        >
          Sair
        </Button>
      </Box>
    </Box>
  );

  return (
    <Box sx={{ display: 'flex' }}>
      <CssBaseline />
      
      <AppBar 
        position="fixed" 
        sx={{ 
          zIndex: (theme) => theme.zIndex.drawer + 1, 
          color: 'text.primary',
          width: { sm: 'calc(100% - 240px)' },
          ml: { sm: '240px' },
        }}
      >
        <Toolbar>
          <IconButton
            color="inherit"
            aria-label="Abrir menu"
            edge="start"
            onClick={handleDrawerToggle}
            sx={{ mr: 2, display: { sm: 'none' } }}
          >
            <Menu size={24} color="#38BDF8" />
          </IconButton>
          
          <Typography variant="h6" noWrap component="div" sx={{ flexGrow: 1, fontWeight: 700, color: 'primary.main' }}>
            Gerenciador
          </Typography>
          
          {user && (
            <Box sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
              <UserCircle size={20} color="#38BDF8" />
              <Typography variant="body1" sx={{ mr: 2, fontWeight: 500 }}>
                {user.name}
              </Typography>
            </Box>
          )}
        </Toolbar>
      </AppBar>

      <Drawer
        variant="temporary"
        open={mobileOpen}
        onClose={handleDrawerToggle}
        ModalProps={{
          keepMounted: true, 
        }}
        sx={{
          display: { xs: 'block', sm: 'none' },
          '& .MuiDrawer-paper': { boxSizing: 'border-box', width: '240px' },
        }}
      >
        {drawerContent}
      </Drawer>

      <Drawer
        variant="permanent"
        sx={{
          display: { xs: 'none', sm: 'block' },
          '& .MuiDrawer-paper': { boxSizing: 'border-box', width: '240px' },
        }}
        open
      >
        {drawerContent}
      </Drawer>

      <Box 
        component="main" 
        sx={{ 
          flexGrow: 1, 
          p: 3, 
          width: { xs: '100%', sm: 'calc(100% - 240px)' },
          maxWidth: '100%',
          overflowX: 'hidden',
          mt: 8 
        }}
      >
        <Outlet />
      </Box>
    </Box>
  );
}
