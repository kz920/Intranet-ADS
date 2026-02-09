import React from 'react';
import { MOCK_NEWS } from '../constants';
import { Clock, Calendar, ArrowRight, Activity } from 'lucide-react';
import { BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer, CartesianGrid } from 'recharts';

const data = [
  { name: 'Lun', visites: 400 },
  { name: 'Mar', visites: 300 },
  { name: 'Mer', visites: 550 },
  { name: 'Jeu', visites: 480 },
  { name: 'Ven', visites: 390 },
];

export const Dashboard: React.FC = () => {
  return (
    <div className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {/* Welcome Card */}
        <div className="md:col-span-2 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg">
          <h2 className="text-2xl font-bold mb-2">Bonjour, Jean ! 👋</h2>
          <p className="opacity-90 mb-4">Voici ce qui se passe chez ADS aujourd'hui.</p>
          <div className="flex space-x-4 mt-6">
             <div className="bg-white/20 backdrop-blur-sm rounded-lg p-3 flex items-center">
                <Clock size={18} className="mr-2" />
                <span className="text-sm font-medium">10:42 AM</span>
             </div>
             <div className="bg-white/20 backdrop-blur-sm rounded-lg p-3 flex items-center">
                <Calendar size={18} className="mr-2" />
                <span className="text-sm font-medium">23 Oct 2023</span>
             </div>
          </div>
        </div>

        {/* Quick Stats */}
        <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
            <h3 className="text-slate-500 text-sm font-medium uppercase tracking-wider">Activité Récente</h3>
            <div className="flex-1 mt-4">
               <ResponsiveContainer width="100%" height={100}>
                  <BarChart data={data}>
                     <Bar dataKey="visites" fill="#4f46e5" radius={[4, 4, 0, 0]} />
                  </BarChart>
               </ResponsiveContainer>
            </div>
            <div className="flex items-center text-green-600 text-sm font-medium mt-2">
                <Activity size={16} className="mr-1" /> +12% cette semaine
            </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
         {/* News Feed */}
        <div className="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-lg font-bold text-slate-800">Actualités Entreprise</h3>
            <button className="text-blue-600 text-sm font-medium flex items-center hover:underline">
                Voir tout <ArrowRight size={16} className="ml-1" />
            </button>
          </div>
          <div className="space-y-4">
            {MOCK_NEWS.map((news) => (
              <div key={news.id} className="group flex items-start p-3 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer border border-transparent hover:border-slate-100">
                <div className={`w-12 h-12 rounded-lg flex-shrink-0 flex items-center justify-center text-white font-bold mr-4 ${
                    news.category === 'RH' ? 'bg-purple-500' : 
                    news.category === 'IT' ? 'bg-blue-500' : 'bg-green-500'
                }`}>
                    {news.category.substring(0, 2)}
                </div>
                <div>
                  <h4 className="font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">{news.title}</h4>
                  <p className="text-sm text-slate-500 line-clamp-2 mt-1">{news.summary}</p>
                  <span className="text-xs text-slate-400 mt-2 block">{news.date}</span>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Quick Links / Tools */}
        <div className="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 className="text-lg font-bold text-slate-800 mb-6">Accès Rapide</h3>
            <div className="grid grid-cols-2 gap-4">
                {[
                    { label: 'Demande de Congés', color: 'bg-orange-100 text-orange-600' },
                    { label: 'Note de Frais', color: 'bg-green-100 text-green-600' },
                    { label: 'Réserver une salle', color: 'bg-purple-100 text-purple-600' },
                    { label: 'Support IT', color: 'bg-red-100 text-red-600' },
                ].map((item, idx) => (
                    <button key={idx} className={`${item.color} p-4 rounded-xl text-left font-semibold text-sm hover:opacity-80 transition-opacity h-24 flex items-end`}>
                        {item.label}
                    </button>
                ))}
            </div>
        </div>
      </div>
    </div>
  );
};