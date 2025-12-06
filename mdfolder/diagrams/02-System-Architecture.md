# System Architecture Diagram

## PinePix Technical Architecture

```mermaid
graph TB
    subgraph "CLIENT LAYER"
        C1[Web Browser]
        C2[Mobile Browser]
        C3[Admin Panel]
    end
    
    subgraph "PRESENTATION LAYER"
        P1[Views/UI<br/>PHP]
        P2[JavaScript<br/>Vanilla]
        P3[Bootstrap 5]
    end
    
    subgraph "APPLICATION LAYER"
        A1[Router<br/>router.php]
        A2[Helpers<br/>Auth, Helper, Mail]
        A3[Controllers<br/>Public Pages]
    end
    
    subgraph "BUSINESS LOGIC LAYER"
        B1[Auth Logic]
        B2[Farm/Shop<br/>Management]
        B3[Chatbot Logic]
        B4[Price Scraper]
    end
    
    subgraph "DATA LAYER"
        D1[Database<br/>MySQL 8]
        D2[File Storage<br/>Uploads]
        D3[Cache<br/>JSON Files]
    end
    
    subgraph "EXTERNAL SERVICES"
        E1[Google Maps<br/>Places API]
        E2[Google Gemini<br/>API]
        E3[Price Scraper<br/>External Source]
        E4[SMTP<br/>Email Service]
    end
    
    C1 -->|HTTP/HTTPS| P1
    C2 -->|HTTP/HTTPS| P1
    C3 -->|HTTP/HTTPS| P1
    
    P1 --> A1
    P2 --> A1
    P3 --> P1
    
    A1 --> A2
    A1 --> A3
    A2 --> B1
    A2 --> B2
    A3 --> B3
    
    B1 --> D1
    B2 --> D1
    B3 --> D1
    B4 --> D1
    
    B2 --> D2
    B4 --> D3
    
    B2 --> E1
    B3 --> E2
    B4 --> E3
    B1 --> E4
    
    style C1 fill:#3b82f6,stroke:#2563eb,color:#fff
    style C2 fill:#3b82f6,stroke:#2563eb,color:#fff
    style C3 fill:#3b82f6,stroke:#2563eb,color:#fff
    style P1 fill:#10b981,stroke:#059669,color:#fff
    style P2 fill:#10b981,stroke:#059669,color:#fff
    style P3 fill:#10b981,stroke:#059669,color:#fff
    style A1 fill:#f59e0b,stroke:#d97706,color:#fff
    style A2 fill:#f59e0b,stroke:#d97706,color:#fff
    style A3 fill:#f59e0b,stroke:#d97706,color:#fff
    style B1 fill:#8b5cf6,stroke:#7c3aed,color:#fff
    style B2 fill:#8b5cf6,stroke:#7c3aed,color:#fff
    style B3 fill:#8b5cf6,stroke:#7c3aed,color:#fff
    style B4 fill:#8b5cf6,stroke:#7c3aed,color:#fff
    style D1 fill:#ec4899,stroke:#db2777,color:#fff
    style D2 fill:#ec4899,stroke:#db2777,color:#fff
    style D3 fill:#ec4899,stroke:#db2777,color:#fff
    style E1 fill:#06b6d4,stroke:#0891b2,color:#fff
    style E2 fill:#06b6d4,stroke:#0891b2,color:#fff
    style E3 fill:#06b6d4,stroke:#0891b2,color:#fff
    style E4 fill:#06b6d4,stroke:#0891b2,color:#fff
```

## System Architecture (Text Format)

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Web Browser │  │ Mobile Browser│  │  Admin Panel │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
                            │
                            │ HTTP/HTTPS
                            │
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Views/UI   │  │  JavaScript  │  │   Bootstrap  │     │
│  │   (PHP)      │  │   (Vanilla)  │  │      5       │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
                            │
                            │
┌─────────────────────────────────────────────────────────────┐
│                     APPLICATION LAYER                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Router     │  │   Helpers    │  │   Controllers│     │
│  │  (router.php)│  │  (Auth, etc) │  │   (Public)   │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
                            │
                            │
┌─────────────────────────────────────────────────────────────┐
│                      BUSINESS LOGIC LAYER                   │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │  Auth Logic  │  │  Farm/Shop   │  │  Chatbot     │     │
│  │              │  │  Management  │  │  Logic       │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
                            │
                            │
┌─────────────────────────────────────────────────────────────┐
│                        DATA LAYER                            │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Database   │  │   File       │  │   Cache      │     │
│  │   (MySQL 8)  │  │   Storage    │  │   (JSON)     │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
                            │
                            │
┌─────────────────────────────────────────────────────────────┐
│                    EXTERNAL SERVICES                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │ Google Maps  │  │ Google Gemini│  │ Price Scraper│     │
│  │ Places API   │  │     API      │  │  (External)  │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
```

