export interface Employee {
  id: string;
  firstName: string;
  lastName: string;
  email: string;
  position: string;
  department: string;
  phone: string;
  avatar: string;
}

export interface NewsItem {
  id: string;
  title: string;
  summary: string;
  date: string;
  category: 'RH' | 'IT' | 'General' | 'Event';
}

export interface DocumentItem {
  id: string;
  name: string;
  type: 'pdf' | 'docx' | 'xlsx';
  size: string;
  updatedAt: string;
}

export interface User {
  username: string;
  role: string;
}

export enum NavSection {
  DASHBOARD = 'DASHBOARD',
  DIRECTORY = 'DIRECTORY',
  DOCUMENTS = 'DOCUMENTS',
  ASSISTANT = 'ASSISTANT'
}

export interface ChatMessage {
  id: string;
  role: 'user' | 'model';
  text: string;
  timestamp: Date;
  isError?: boolean;
}