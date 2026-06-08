import { 
    BrowserRouter as Router, 
    Routes, 
    Route, 
    Navigate 
} from 'react-router-dom';
import LoginPage from '../pages/LoginPage';
import RegisterPage from '../pages/RegisterPage';
import LivrosListPage from '../pages/LivrosListPage';
import MeusEmprestimosPage from '../pages/MeusEmprestimosPage';
import PrivateRoute from '../components/PrivateRoute';
import SidebarLayout from '../components/SidebarLayout';


export default function AppRoutes() {
    return (
        <Router>
            <Routes>
                <Route path="/login" element={<LoginPage />} />
                <Route path="/register" element={<RegisterPage />} />

                <Route element={<PrivateRoute />}>
                    <Route element={<SidebarLayout />}>
                        <Route path="/livros" element={<LivrosListPage />} />
                        <Route path="/meus-emprestimos" element={<MeusEmprestimosPage />} />
                    </Route>
                </Route>

                <Route path="*" element={<Navigate to="/login" replace />} />
            </Routes>
        </Router>
    )
}