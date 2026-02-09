import React, { useState } from 'react';
import { MOCK_EMPLOYEES } from '../constants';
import { Search, Mail, Phone, Briefcase } from 'lucide-react';

export const Directory: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');

  const filteredEmployees = MOCK_EMPLOYEES.filter(emp =>
    emp.firstName.toLowerCase().includes(searchTerm.toLowerCase()) ||
    emp.lastName.toLowerCase().includes(searchTerm.toLowerCase()) ||
    emp.department.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="space-y-6">
      <div className="flex flex-col md:flex-row justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <h2 className="text-xl font-bold text-slate-800 mb-4 md:mb-0">Annuaire des Employés</h2>
        <div className="relative w-full md:w-96">
          <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400" size={18} />
          <input
            type="text"
            placeholder="Rechercher par nom ou département..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
      </div>

<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredEmployees.map((emp) => (
          <div key={emp.id} className="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow flex flex-col items-center text-center">
            <img 
              src={emp.avatar} 
              alt={`${emp.firstName} ${emp.lastName}`} 
              className="w-24 h-24 rounded-full object-cover mb-4 border-4 border-slate-50"
            />
            
            <h3 className="text-lg font-semibold text-slate-800">{emp.firstName} {emp.lastName}</h3>
            <p className="text-blue-600 text-sm font-medium mb-4">{emp.role}</p>
            
            <div className="w-full space-y-2 text-sm text-slate-600">
              <div className="flex items-center justify-center gap-2">
                <Briefcase size={14} />
                <span>{emp.department}</span>
              </div>
              <div className="flex items-center justify-center gap-2">
                <Mail size={14} />
                <a href={`mailto:${emp.email}`} className="hover:text-blue-600">{emp.email}</a>
              </div>
              {/* Optionnel : Ajout du téléphone si présent dans vos données */}
              {emp.phone && (
                <div className="flex items-center justify-center gap-2">
                  <Phone size={14} />
                  <span>{emp.phone}</span>
                </div>
              )}
            </div>
          </div>
        ))} {/* <--- Fermeture du .map */}
      </div>
    </div>
  ); // <--- Fermeture du return
}; // <--- Fermeture du composant
