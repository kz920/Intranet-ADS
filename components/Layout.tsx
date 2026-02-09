import React from 'react';
import { NavSection, User as UserType } from '../types';
import { LayoutDashboard, Users, FileText, Bot, Menu, Bell, Search, LogOut } from 'lucide-react';

interface LayoutProps {
  currentSection: NavSection;
  onNavigate: (section: NavSection) => void;
  children: React.ReactNode;
  user: UserType;
  onLogout: () => void;
}

export const Layout: React.FC<LayoutProps> = ({ currentSection, onNavigate, children, user, onLogout }) => {
  const [isSidebarOpen, setIsSidebarOpen] = React.useState(true);

  const NavItem = ({ section, icon: Icon, label }: { section: NavSection; icon: any; label: string }) => (
    <button
      onClick={() => onNavigate(section)}
      className={`flex items-center w-full px-4 py-3 mb-2 rounded-lg transition-colors ${
        currentSection === section
          ? 'bg-blue-600 text-white shadow-lg'
          : 'text-slate-600 hover:bg-slate-100'
      }`}
    >
      <Icon size={20} className="mr-3" />
      <span className="font-medium">{label}</span>
    </button>
  );

  return (
    <div className="flex h-screen bg-slate-50 overflow-hidden">
      {/* Sidebar */}
      <aside
        className={`${
          isSidebarOpen ? 'w-64' : 'w-20'
        } bg-white border-r border-slate-200 transition-all duration-300 flex flex-col z-20`}
      >
        <div className="h-16 flex items-center justify-center border-b border-slate-100">
          {isSidebarOpen ? (
            <h1 className="text-xl font-bold text-blue-700 tracking-tight">ADS Intranet</h1>
          ) : (
            <div className="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold">A</div>
          )}
        </div>

        <nav className="flex-1 p-4">
          <NavItem section={NavSection.DASHBOARD} icon={LayoutDashboard} label={isSidebarOpen ? "Tableau de bord" : ""} />
          <NavItem section={NavSection.DIRECTORY} icon={Users} label={isSidebarOpen ? "Annuaire" : ""} />
          <NavItem section={NavSection.DOCUMENTS} icon={FileText} label={isSidebarOpen ? "Documents" : ""} />
          <div className="my-4 border-t border-slate-100"></div>
          <NavItem section={NavSection.ASSISTANT} icon={Bot} label={isSidebarOpen ? "Assistant IA" : ""} />
        </nav>

        <div className="p-4 border-t border-slate-100">
           <button 
            onClick={() => setIsSidebarOpen(!isSidebarOpen)}
            className="flex items-center justify-center w-full p-2 text-slate-400 hover:text-blue-600 rounded-lg hover:bg-slate-50"
           >
             <Menu size={20} />
           </button>
        </div>
      </aside>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Header */}
        <header className="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-10">
            <div className="flex items-center bg-slate-100 rounded-full px-4 py-2 w-96">
                <Search size={18} className="text-slate-400 mr-2" />
                <input 
                    type="text" 
                    placeholder="Rechercher..." 
                    className="bg-transparent border-none focus:outline-none text-sm w-full text-slate-700"
                />
            </div>

            <div className="flex items-center space-x-4">
                <button className="p-2 text-slate-500 hover:bg-slate-100 rounded-full relative">
                    <Bell size={20} />
                    <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div className="flex items-center pl-4 border-l border-slate-200">
                    <div className="flex items-center mr-4">
                        <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold mr-2 uppercase">
                            {user.username.substring(0, 2)}
                        </div>
                        <span className="text-sm font-medium text-slate-700">{user.username}</span>
                    </div>
                    <button 
                        onClick={onLogout}
                        className="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors"
                        title="Se déconnecter"
                    >
                        <LogOut size={18} />
                    </button>
                </div>
            </div>
        </header>

        {/* Scrollable Page Content */}
        <main className="flex-1 overflow-auto p-8">
          {children}
        </main>
      </div>
    </div>
  );
};