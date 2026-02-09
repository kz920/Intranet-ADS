import { Employee, NewsItem, DocumentItem } from './types';

export const MOCK_EMPLOYEES: Employee[] = [
  {
    id: '1',
    firstName: 'Jean',
    lastName: 'Dupont',
    email: 'jean.dupont@ads.com',
    position: 'Directeur Commercial',
    department: 'Ventes',
    phone: '+33 6 12 34 56 78',
    avatar: 'https://picsum.photos/100/100?random=1'
  },
  {
    id: '2',
    firstName: 'Marie',
    lastName: 'Curie',
    email: 'marie.curie@ads.com',
    position: 'Lead Developer',
    department: 'IT',
    phone: '+33 6 98 76 54 32',
    avatar: 'https://picsum.photos/100/100?random=2'
  },
  {
    id: '3',
    firstName: 'Pierre',
    lastName: 'Martin',
    email: 'pierre.martin@ads.com',
    position: 'RH Manager',
    department: 'Ressources Humaines',
    phone: '+33 6 11 22 33 44',
    avatar: 'https://picsum.photos/100/100?random=3'
  },
  {
    id: '4',
    firstName: 'Sophie',
    lastName: 'Bernard',
    email: 'sophie.bernard@ads.com',
    position: 'Designer UI/UX',
    department: 'Marketing',
    phone: '+33 6 55 66 77 88',
    avatar: 'https://picsum.photos/100/100?random=4'
  }
];

export const MOCK_NEWS: NewsItem[] = [
  {
    id: '1',
    title: 'Nouvelle politique de télétravail',
    summary: 'La direction a validé la nouvelle charte de télétravail applicable dès le 1er octobre.',
    date: '2023-09-28',
    category: 'RH'
  },
  {
    id: '2',
    title: 'Maintenance serveur prévue ce weekend',
    summary: 'Une interruption de service est à prévoir samedi soir entre 22h et 02h du matin.',
    date: '2023-09-27',
    category: 'IT'
  },
  {
    id: '3',
    title: 'Résultats financiers Q3',
    summary: 'Nous avons dépassé nos objectifs de 15%. Bravo à toutes les équipes !',
    date: '2023-09-25',
    category: 'General'
  }
];

export const MOCK_DOCUMENTS: DocumentItem[] = [
  { id: '1', name: 'Charte_Informatique.pdf', type: 'pdf', size: '2.4 MB', updatedAt: '2023-01-15' },
  { id: '2', name: 'Note_de_frais_template.xlsx', type: 'xlsx', size: '45 KB', updatedAt: '2023-03-10' },
  { id: '3', name: 'Guide_Accueil_2023.docx', type: 'docx', size: '1.1 MB', updatedAt: '2023-06-20' },
  { id: '4', name: 'Plan_Marketing_2024.pdf', type: 'pdf', size: '5.6 MB', updatedAt: '2023-09-01' },
];