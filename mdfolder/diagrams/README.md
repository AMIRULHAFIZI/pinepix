# PinePix Project Diagrams

This folder contains all diagrams referenced in the PROJECT_DOCUMENTATION.md file.

## Diagram Files

1. **01-WBS-Chart.md** - Work Breakdown Structure chart showing all project tasks and subtasks
2. **02-System-Architecture.md** - Technical architecture diagram showing system layers and components
3. **03-Database-Architecture.md** - Entity Relationship Diagram (ERD) showing database structure and relationships
4. **04-User-Flow-Diagrams.md** - User flow diagrams for key processes:
   - Registration Flow
   - Farm Management Flow
   - Chatbot Flow
   - Login Flow
   - Admin Approval Flow
5. **05-Gantt-Chart.md** - Project timeline Gantt chart showing task scheduling

## How to View Diagrams

### Option 1: GitHub/GitLab
These diagrams use Mermaid syntax which is natively supported by GitHub and GitLab. Simply view the files directly on the platform.

### Option 2: VS Code
Install the "Markdown Preview Mermaid Support" extension to view diagrams in VS Code.

### Option 3: Online Mermaid Editor
1. Copy the mermaid code block from any diagram file
2. Paste it into https://mermaid.live/
3. View and export the diagram

### Option 4: Mermaid CLI
Install Mermaid CLI and generate images:
```bash
npm install -g @mermaid-js/mermaid-cli
mmdc -i 01-WBS-Chart.md -o 01-WBS-Chart.png
```

## Diagram Types

### Mermaid Diagrams
- **Flowchart**: Used for user flows and process flows
- **Graph**: Used for system architecture
- **ER Diagram**: Used for database relationships
- **Gantt Chart**: Used for project timeline

### Text Diagrams
Each file also includes a text-based version of the diagram for compatibility and documentation purposes.

## Integration with Documentation

These diagrams are referenced in the main PROJECT_DOCUMENTATION.md file:
- Section 1.3: WBS Chart
- Section 5.2: Gantt Chart
- Section 9.2.1: System Architecture
- Section 9.2.2: Database Architecture
- Section 9.4: User Flow Diagrams

## Notes

- All diagrams are created using Mermaid syntax for easy rendering
- Text versions are included for compatibility
- Diagrams can be exported as PNG/SVG using Mermaid tools
- Diagrams are version-controlled with the project documentation

