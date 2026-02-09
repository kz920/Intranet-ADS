import React, { useState, useEffect } from 'react';
import { Layout } from './components/Layout';
import { Dashboard } from './components/Dashboard';
import { Directory } from './components/Directory';
import { Login } from './components/Login';
import { NavSection, User } from './types';
import { Loader2 } from 'lucide-react';

function App() {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const [currentSection, setCurrentSection] = useState<NavSection>(NavSection.DASHBOARD);

  // Vérifier la session au chargement de l'application
  useEffect(() => {
    const checkAuth = async () => {
      try {
        const res = await fetch('/api/auth/check');
        const text = await res.text();
        
        try {
            const data = JSON.parse(text);
            if (data.authenticated && data.user) {
              setUser(data.user);
            }
        } catch (e) {
            // Ignorer l'erreur silencieusement si l'utilisateur n'est pas connecté ou si le JSON est malformé
            console.warn("Réponse API non valide:", text);
        }
      } catch (error) {
        console.error("Erreur réseau vérification session", error);
      } finally {
        setLoading(false);
      }
    };

    checkAuth();
  }, []);

  const handleLogout = async () => {
    try {
      await fetch('/api/auth/logout', { method: 'POST' });
      setUser(null);
    } catch (error) {
      console.error("Erreur déconnexion", error);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <Loader2 className="w-10 h-10 text-blue-600 animate-spin" />
      </div>
    );
  }

  if (!user) {
    return <Login onLogin={setUser} />;
  }

  return (
    <Layout 
      currentSection={currentSection} 
      onNavigate={setCurrentSection}
      user={user}
      onLogout={handleLogout}
    >
      {currentSection === NavSection.DASHBOARD && <Dashboard />}
      {currentSection === NavSection.DIRECTORY && <Directory />}
      {currentSection === NavSection.DOCUMENTS && (
        <div className="p-8 text-center text-slate-500">Module Documents en construction</div>
      )}
      {currentSection === NavSection.ASSISTANT && (
        <div className="p-8 text-center text-slate-500">Module Assistant IA en construction</div>
      )}
    </Layout>
  );
}

export default App;