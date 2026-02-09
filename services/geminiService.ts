import { GoogleGenAI } from "@google/genai";

const apiKey = process.env.API_KEY || '';
const ai = new GoogleGenAI({ apiKey });

export const generateResponse = async (prompt: string): Promise<string> => {
  if (!apiKey) {
    return "Erreur de configuration: Clé API manquante.";
  }

  try {
    const modelId = 'gemini-3-flash-preview';
    const response = await ai.models.generateContent({
      model: modelId,
      contents: prompt,
      config: {
        systemInstruction: "Tu es l'assistant virtuel intelligent de l'intranet de l'entreprise ADS. Tu aides les employés à rédiger des emails, résumer des documents, ou trouver des informations générales. Sois professionnel, concis et courtois.",
      }
    });

    return response.text || "Désolé, je n'ai pas pu générer une réponse.";
  } catch (error) {
    console.error("Gemini API Error:", error);
    return "Une erreur est survenue lors de la communication avec l'assistant.";
  }
};