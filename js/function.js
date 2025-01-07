document.addEventListener('DOMContentLoaded', () => {
    // Simuler des données de demandes de décaissement
    const demandes = [
      { titre: 'Demande 1', montant: 1000, date: '01/04/2024', statut: 'En attente' },
      { titre: 'Demande 2', montant: 2000, date: '02/04/2024', statut: 'Approuvé' },
      { titre: 'Demande 3', montant: 1500, date: '03/04/2024', statut: 'Rejeté' }
    ];
  
    // Sélection de l'élément conteneur des demandes
    const demandesContainer = document.getElementById('demandesContainer');
  
    // Boucle à travers les demandes et les affiche dans le conteneur
    demandes.forEach(demande => {
      const demandeElement = document.createElement('div');
      demandeElement.classList.add('demande');
      demandeElement.innerHTML = `
        <h2>${demande.titre}</h2>
        <p>Montant: ${demande.montant}</p>
        <p>Date: ${demande.date}</p>
        <p>Statut: ${demande.statut}</p>
      `;
      demandesContainer.appendChild(demandeElement);
    });
  });
  