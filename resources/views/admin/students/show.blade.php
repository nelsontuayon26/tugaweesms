<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { 
            background: #f8fafc;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            overflow-x: hidden;
            background: #f8fafc;
        }

        @media (max-width: 1024px) {
            .main-wrapper { margin-left: 0; }
        }

        /* Glass Card */
        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        /* Profile Header - Enhanced */
        .profile-header {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
        }

        /* Photo Container */
        .photo-container {
            position: relative;
            display: inline-block;
        }

        .photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            border: 4px solid white;
            box-shadow: 0 10px 30px -5px rgba(99, 102, 241, 0.4);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .photo-placeholder:hover {
            transform: scale(1.05);
        }

        .photo-placeholder.ring-enrolled {
            box-shadow: 0 0 0 4px #10b981, 0 10px 30px -5px rgba(99, 102, 241, 0.4);
        }

        .photo-placeholder.ring-pending {
            box-shadow: 0 0 0 4px #f59e0b, 0 10px 30px -5px rgba(99, 102, 241, 0.4);
        }

        .photo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Status Badge on Avatar */
        .status-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: white;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            animation: scaleIn 0.3s ease;
        }

        .status-badge.status-enrolled {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .status-badge.status-pending {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }

        /* Status Pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pill-enrolled {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .pill-pending {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .dot-enrolled { background: #10b981; }
        .dot-pending { background: #ef4444; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Enrollment Info Bar */
        .enrollment-bar {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 20px;
            margin-top: 16px;
        }

        .enrollment-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .enrollment-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .enrollment-icon.grade {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #6366f1;
        }

        .enrollment-icon.section {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: #10b981;
        }

        .enrollment-icon.year {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            color: #f59e0b;
        }

        .enrollment-details {
            display: flex;
            flex-direction: column;
        }

        .enrollment-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .enrollment-value {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }

        .enrollment-divider {
            width: 1px;
            height: 40px;
            background: #e2e8f0;
        }

        @media (max-width: 768px) {
            .enrollment-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            .enrollment-divider {
                width: 100%;
                height: 1px;
            }
        }

        /* Secondary Info */
        .secondary-info {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
        }

        .info-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            font-size: 13px;
            color: #475569;
            font-weight: 500;
        }

        .info-pill i {
            color: #6366f1;
            font-size: 12px;
        }

        /* Assignment Card */
        .assignment-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .assignment-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        .assignment-header i {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .assignment-header h3 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .assignment-body {
            padding: 24px;
        }

        .assignment-flow {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .assignment-node {
            flex: 1;
            min-width: 0;
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .assignment-node.active {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border-color: #10b981;
        }

        .assignment-node.pending {
            background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
            border-color: #ef4444;
        }

        .node-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 12px;
        }

        .node-icon.grade {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #6366f1;
        }

        .node-icon.section {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: #10b981;
        }

        .node-icon.year {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            color: #f59e0b;
        }

        .node-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .node-value {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .node-meta {
            font-size: 12px;
            color: #64748b;
        }

        .assignment-arrow {
            color: #cbd5e1;
            font-size: 24px;
            flex-shrink: 0;
        }

        @media (max-width: 1024px) {
            .assignment-flow {
                flex-direction: column;
            }
            .assignment-arrow {
                transform: rotate(90deg);
            }
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card.highlight {
            border: 2px solid #e0e7ff;
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-badge {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 10px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            border-radius: 20px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }

        .stat-icon.indigo { background: #eef2ff; color: #6366f1; }
        .stat-icon.emerald { background: #ecfdf5; color: #10b981; }
        .stat-icon.amber { background: #fffbeb; color: #f59e0b; }
        .stat-icon.purple { background: #f5f3ff; color: #8b5cf6; }

        .stat-value {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
        }

        .stat-meta {
            margin-top: 8px;
            font-size: 12px;
            color: #64748b;
        }

        /* Tab Navigation */
        .tabs-wrapper {
            background: white;
            border-radius: 16px;
            padding: 6px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .tabs-container {
            display: flex;
            gap: 4px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .tabs-container::-webkit-scrollbar { display: none; }

        .tab-btn {
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            color: #64748b;
            border-radius: 12px;
            transition: all 0.2s ease;
            cursor: pointer;
            background: transparent;
            border: none;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #6366f1;
            background: #f8fafc;
        }

        .tab-btn.active {
            color: white;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            box-shadow: 0 4px 12px -2px rgba(99, 102, 241, 0.3);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        /* Info Items */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        @media (max-width: 1024px) {
            .info-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .info-item:hover {
            background: white;
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px -4px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .info-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .info-icon.blue { background: #dbeafe; color: #2563eb; }
        .info-icon.indigo { background: #e0e7ff; color: #4f46e5; }
        .info-icon.pink { background: #fce7f3; color: #db2777; }
        .info-icon.amber { background: #fef3c7; color: #d97706; }
        .info-icon.rose { background: #ffe4e6; color: #e11d48; }
        .info-icon.emerald { background: #d1fae5; color: #059669; }
        .info-icon.cyan { background: #cffafe; color: #0891b2; }
        .info-icon.violet { background: #ede9fe; color: #7c3aed; }

        .info-label {
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        /* Parent Cards */
        .parents-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .parents-grid { grid-template-columns: 1fr; }
        }

        .parent-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .parent-card:hover {
            border-color: #c7d2fe;
            box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.15);
            transform: translateY(-3px);
        }

        .parent-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .parent-avatar {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px -2px rgba(0,0,0,0.1);
        }

        .parent-avatar.father {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        .parent-avatar.mother {
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            color: #9d174d;
        }

        .parent-avatar.guardian {
            background: linear-gradient(135deg, #e9d5ff, #ddd6fe);
            color: #5b21b6;
        }

        .parent-title {
            font-size: 13px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .parent-occupation {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .parent-name {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .parent-contact {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
            font-size: 14px;
            color: #475569;
            font-weight: 600;
        }

        /* Address Section */
        .address-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
        }

        .address-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .address-content {
            padding: 24px;
        }

        .address-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        @media (max-width: 1024px) {
            .address-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .address-grid { grid-template-columns: 1fr; }
        }

        .address-field {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
        }

        .address-field:hover {
            background: white;
            border-color: #cbd5e1;
        }

        .address-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .address-value {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        /* Document Cards */
        .documents-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        @media (max-width: 768px) {
            .documents-grid { grid-template-columns: 1fr; }
        }

        .document-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .document-card:hover {
            background: white;
            border-color: #c7d2fe;
            box-shadow: 0 4px 12px -4px rgba(0, 0, 0, 0.05);
        }

        .document-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .document-icon.blue { background: #dbeafe; color: #2563eb; }
        .document-icon.emerald { background: #d1fae5; color: #059669; }
        .document-icon.amber { background: #fef3c7; color: #d97706; }
        .document-icon.rose { background: #ffe4e6; color: #e11d48; }
        .document-icon.purple { background: #f3e8ff; color: #7c22ce; }
        .document-icon.cyan { background: #cffafe; color: #0e7490; }

        .document-info h4 {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .document-info p {
            font-size: 13px;
            color: #64748b;
        }

        .document-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .document-status.uploaded {
            background: #ecfdf5;
            color: #065f46;
        }

        .document-status.missing {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px -2px rgba(99, 102, 241, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #475569;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        /* Floating Action Buttons */
        .fab-container {
            position: fixed;
            bottom: 32px;
            right: 32px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 50;
        }

        .fab-btn {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 15px -3px rgba(0,0,0,0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
        }

        .fab-btn:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 30px -5px rgba(0,0,0,0.3);
        }

        .fab-btn.edit {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .fab-btn.delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .fab-btn.back, .fab-btn.print {
            background: white;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .fab-btn.back:hover, .fab-btn.print:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .fab-tooltip {
            position: absolute;
            right: 70px;
            background: #0f172a;
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            pointer-events: none;
        }

        .fab-btn:hover .fab-tooltip {
            opacity: 1;
            visibility: visible;
            right: 75px;
        }

        /* Toast */
        .toast {
            position: fixed;
            top: 24px;
            right: 24px;
            background: white;
            border-left: 4px solid #10b981;
            padding: 16px 24px;
            border-radius: 16px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 16px;
            z-index: 100;
            transform: translateX(400px);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            max-width: 400px;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-icon {
            width: 44px;
            height: 44px;
            background: #ecfdf5;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-icon i {
            color: #10b981;
            font-size: 20px;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-header i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6366f1;
            font-size: 18px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }

        /* SF1 Table */
        .sf1-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }
        .sf1-table th,
        .sf1-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
            vertical-align: middle;
        }
        .sf1-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 7px;
        }

        /* Print styles */
        @media print {
            .fab-container, .sidebar, .toast, .tabs-wrapper { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
            .main-content { overflow: visible !important; padding: 20px; }
            .tab-content { display: block !important; }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: #f1f5f9;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .empty-state-icon i {
            font-size: 32px;
            color: #94a3b8;
        }

        .empty-state h4 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
            color: #64748b;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Footer Info */
        .footer-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 0;
            margin-top: 32px;
            border-top: 1px solid #e2e8f0;
        }

        .footer-meta {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .footer-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
        }

        .footer-meta-item i {
            color: #94a3b8;
        }

        .footer-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #10b981;
        }

        .footer-status::before {
            content: '';
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Audit Trail */
        .audit-item {
            position: relative;
            padding-left: 32px;
            padding-bottom: 24px;
            border-left: 2px solid #e2e8f0;
            margin-left: 16px;
        }

        .audit-item::before {
            content: '';
            position: absolute;
            left: -9px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #6366f1;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #6366f1;
        }

        .audit-item:last-child {
            border-left: 2px solid transparent;
            padding-bottom: 0;
        }

        .change-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            margin-top: 4px;
        }

        .change-field {
            font-weight: 600;
            color: #64748b;
        }

        .change-old {
            text-decoration: line-through;
            color: #ef4444;
            background: #fee2e2;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .change-new {
            color: #10b981;
            font-weight: 600;
            background: #d1fae5;
            padding: 2px 8px;
            border-radius: 6px;
        }



                /* ============================================
           CREATE PUPIL MATCHING STYLES
           ============================================ */
        
        /* Form Section Header - Gray gradient with teal bottom border */
        .form-section-header {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border: 2px solid #cbd5e1;
            border-bottom: 3px solid #0d9488;
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #1e293b;
            text-align: center;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        /* Accent variant (teal header) */
        .form-section-header.accent {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: white;
            border-color: #0f766e;
        }

        /* Form Section Body - White with border */
        .form-section-body {
            border: 2px solid #cbd5e1;
            border-top: none;
            padding: 1.5rem;
            background: white;
            border-radius: 0 0 0.5rem 0.5rem;
            margin-bottom: 1.5rem;
        }

        /* Form Label */
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.375rem;
            display: block;
        }

        /* Form Input */
        .form-input {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 2px solid #cbd5e1;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            outline: none;
        }
        .form-input:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }
        .form-input.bg-slate-100 {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Custom Radio Button */
        .custom-radio {
            appearance: none;
            -webkit-appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            flex-shrink: 0;
            margin: 0;
        }
        .custom-radio:checked {
            background-color: #0d9488;
            border-color: #0d9488;
        }
        .custom-radio:checked::after {
            content: '';
            position: absolute;
            left: 3px;
            top: 3px;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }
        .custom-radio:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Sub-section divider */
        .sub-section {
            border-top: 1px dashed #cbd5e1;
            padding-top: 1rem;
            margin-top: 1rem;
        }
        .sub-section:first-child {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }


                /* SF1 Table Header - Dark Blue DepEd Style */
        .form-section-header.sf1-blue {
            background: #1e3a5f;
            color: white;
            border: 1px solid #1e3a5f;
            border-bottom: 3px solid #0d9488;
        }
    </style>
</head>
<body class="antialiased text-slate-800 overflow-x-hidden" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         @click="mobileOpen = false"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <!-- Toast Notification -->
    @if(session('success'))
    <div id="successToast" class="toast show">
        <div class="toast-icon">
            <i class="fas fa-check"></i>
        </div>
        <div>
            <p class="font-bold text-slate-900 text-sm">Success!</p>
            <p class="text-sm text-slate-600 mt-1">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="dashboard-container">
        @include('admin.includes.sidebar')

        <div class="main-wrapper">
            <div class="main-content">
                <div class="max-w-6xl mx-auto pb-24">

                    <!-- Enhanced Header Section -->
                    <div class="profile-header animate-fade-in">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                            <div class="photo-container">
                                <div class="photo-placeholder {{ $student->status === 'active' ? 'ring-enrolled' : 'ring-pending' }}">
                                    @php
                                        $name = $student->user->name ?? $student->full_name ?? 'Student';
                                        $initials = '';
                                        $words = explode(' ', $name);
                                        foreach($words as $word) {
                                            $initials .= strtoupper(substr($word, 0, 1));
                                            if(strlen($initials) >= 2) break;
                                        }
                                    @endphp
                                    @if($student->user->photo ?? $student->photo_path)
                                        <img src="{{ profile_photo_url($student->user->photo ?? $student->photo_path) }}" alt="Profile">
                                    @else
                                        <span class="text-3xl font-bold">{{ $initials ?: 'ST' }}</span>
                                    @endif
                                </div>
                                <div class="status-badge {{ $student->status === 'active' ? 'status-enrolled' : 'status-pending' }}">
                                    <i class="fas fa-{{ $student->status === 'active' ? 'check' : 'times' }}"></i>
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-3 flex-wrap">
                                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $name }}</h1>
                                    <span class="status-pill {{ $student->status === 'active' ? 'pill-enrolled' : 'pill-pending' }}">
                                        <span class="status-dot {{ $student->status === 'active' ? 'dot-enrolled' : 'dot-pending' }}"></span>
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </div>
                                
                                <!-- Enrollment Info Bar -->
                                <div class="enrollment-bar">
                                    <div class="enrollment-item">
                                        <div class="enrollment-icon grade">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <div class="enrollment-details">
                                            <span class="enrollment-label">Grade Level</span>
                                            <span class="enrollment-value">
    {{ $student->enrollments->last()->gradeLevel->name ?? 'Not Assigned' }}
</span>
                                        </div>
                                    </div>
                                    
                                    <div class="enrollment-divider"></div>
                                    
                                    <div class="enrollment-item">
                                        <div class="enrollment-icon section">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="enrollment-details">
                                            <span class="enrollment-label">Section</span>
                                            <span class="enrollment-value">{{ $student->section->name ?? 'Not Assigned' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="enrollment-divider"></div>
                                    
                                    <div class="enrollment-item">
                                        <div class="enrollment-icon year">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="enrollment-details">
                                            <span class="enrollment-label">School Year</span>
                                            <span class="enrollment-value">{{ $student->enrollments->first()->schoolYear->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Secondary Info -->
                                <div class="secondary-info">
                                    <span class="info-pill">
                                        <i class="fas fa-id-card"></i>
                                        LRN: {{ $student->lrn ?? 'N/A' }}
                                    </span>
                                    <span class="info-pill">
                                        <i class="fas fa-envelope"></i>
                                        {{ $student->user->email ?? 'N/A' }}
                                    </span>
                                    <span class="info-pill">
                                        <i class="fas fa-birthday-cake"></i>
                                        {{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('M d, Y') : 'Not provided' }} ({{ $student->age ?? 'N/A' }} yrs)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <!-- Enhanced Tab Navigation -->
                    <div class="tabs-wrapper animate-fade-in stagger-3">
                        <div class="tabs-container">
                            <button class="tab-btn active" onclick="switchTab('sf1', this)">
                                <i class="fas fa-table"></i>SF1
                            </button>
                            <button class="tab-btn" onclick="switchTab('history', this)">
                                <i class="fas fa-history"></i>History
                            </button>
                        </div>
                    </div>

                    <!-- History Tab -->
                    <div id="history" class="tab-content">
                        <div class="glass-card p-6 animate-fade-in">
                            <div class="section-header">
                                <i class="fas fa-history" style="background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #7c3aed;"></i>
                                <h3>Edit History & Audit Trail</h3>
                            </div>
                            
                            @if($activityLogs && $activityLogs->count() > 0)
                                <div class="space-y-3">
                                    @foreach($activityLogs as $log)
                                        <div class="p-4 bg-white border border-slate-200 rounded-xl">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-{{ $log->action_color }}-100 flex items-center justify-center text-{{ $log->action_color }}-600">
                                                        <i class="fas {{ $log->action_icon }}"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 text-sm capitalize">{{ $log->action }}</p>
                                                        <p class="text-xs text-slate-500">by {{ $log->user?->full_name ?? 'System' }} on {{ $log->created_at->format('M d, Y h:i A') }}</p>
                                                    </div>
                                                </div>
                                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">
                                                    {{ $log->ip_address ?? 'N/A' }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-slate-700 mb-2">{{ $log->description }}</p>
                                            @if($log->old_values || $log->new_values)
                                                @php
                                                    $oldValues = $log->old_values ?? [];
                                                    $newValues = $log->new_values ?? [];
                                                    $hasNested = false;
                                                    foreach (array_merge($oldValues, $newValues) as $v) {
                                                        if (is_array($v)) { $hasNested = true; break; }
                                                    }
                                                @endphp
                                                @if($hasNested)
                                                    <div class="bg-slate-50 rounded-lg p-3 text-xs text-slate-500 italic">
                                                        Changes recorded in legacy format (full record snapshot).
                                                    </div>
                                                @else
                                                    @php
                                                        $allKeys = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                                                        $labelMap = [
                                                            'user_username' => 'Username', 'user_email' => 'Email',
                                                            'user_first_name' => 'First Name', 'user_middle_name' => 'Middle Name',
                                                            'user_last_name' => 'Last Name', 'user_suffix' => 'Suffix',
                                                            'user_photo' => 'Photo', 'user_password' => 'Password',
                                                            'lrn' => 'LRN', 'birthdate' => 'Birth Date',
                                                            'birth_place' => 'Birth Place', 'gender' => 'Gender',
                                                            'nationality' => 'Nationality', 'religion' => 'Religion',
                                                            'father_name' => 'Father\'s Name', 'father_occupation' => 'Father\'s Occupation',
                                                            'mother_name' => 'Mother\'s Name', 'mother_occupation' => 'Mother\'s Occupation',
                                                            'guardian_name' => 'Guardian\'s Name', 'guardian_relationship' => 'Guardian Relationship',
                                                            'guardian_contact' => 'Guardian Contact', 'street_address' => 'Street Address',
                                                            'barangay' => 'Barangay', 'city' => 'City',
                                                            'province' => 'Province', 'zip_code' => 'ZIP Code',
                                                            'grade_level_id' => 'Grade Level', 'section_id' => 'Section',
                                                            'ethnicity' => 'Ethnicity', 'mother_tongue' => 'Mother Tongue',
                                                            'remarks' => 'Remarks',
                                                            'birth_certificate_path' => 'Birth Certificate',
                                                            'report_card_path' => 'Report Card',
                                                            'good_moral_path' => 'Good Moral Certificate',
                                                            'transfer_credential_path' => 'Transfer Credential',
                                                        ];
                                                    @endphp
                                                    <div class="bg-slate-50 rounded-lg p-3 text-xs space-y-1">
                                                        @foreach($allKeys as $key)
                                                            @php
                                                                $oldVal = $oldValues[$key] ?? '';
                                                                $newVal = $newValues[$key] ?? '';
                                                                $label = $labelMap[$key] ?? str_replace('_', ' ', ucfirst($key));
                                                            @endphp
                                                            <div class="flex items-start gap-2 flex-wrap">
                                                                <span class="font-semibold text-slate-500 w-32 shrink-0">{{ $label }}:</span>
                                                                <span class="text-rose-600 line-through">{{ $oldVal !== '' && $oldVal !== null ? $oldVal : '(empty)' }}</span>
                                                                <span class="text-slate-400">→</span>
                                                                <span class="text-emerald-600 font-medium">{{ $newVal !== '' && $newVal !== null ? $newVal : '(empty)' }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                    <h4>No Activity History</h4>
                                    <p>No changes have been recorded yet. When the student record is created, updated, or deleted, those actions will appear here.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                          <!-- SF1 Tab -->
                    <div id="sf1" class="tab-content active">
                                          @php
                            $enrollment = $student->enrollments->first();
                            $sectionName = $enrollment?->section?->name ?? $student->section?->name ?? 'Unassigned';
                            $gradeLevelName = $enrollment?->section?->gradeLevel?->name ?? $student->gradeLevel?->name ?? 'N/A';
                            $schoolYearName = $activeSchoolYear?->name ?? 'N/A';
                            $status = $enrollment?->status ?? 'pending';
                            $pupilType = $enrollment?->type ?? $student->type ?? 'new';
                            
                            $user = $student->user;
                            $gender = strtoupper($student->gender ?? '');
                            $sexCode = $gender == 'MALE' || $gender == 'M' ? 'M' : ($gender == 'FEMALE' || $gender == 'F' ? 'F' : '');
                            $birthDate = $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('m/d/Y') : '';
                            $age = $student->age ?? '';
                            $fullName = trim(($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? ''));
                            
                            $addressParts = array_filter([
                                $student->street_address ?? '',
                                $student->barangay ?? '',
                                $student->city ?? '',
                                ($student->province ?? '') . ($student->zip_code ? ' ' . $student->zip_code : ''),
                            ]);
                            $address = implode(', ', $addressParts);
                            $gradeSection = trim($gradeLevelName . ($gradeLevelName && $sectionName ? ' - ' : '') . $sectionName);
                            $enrolledSince = $enrollment?->enrollment_date ? \Carbon\Carbon::parse($enrollment->enrollment_date)->format('m/d/Y') : '';
                            
                            $sf1Docs = [
                                ['path' => $student->birth_certificate_path, 'name' => 'Birth Certificate'],
                                ['path' => $student->report_card_path, 'name' => 'Report Card'],
                                ['path' => $student->good_moral_path, 'name' => 'Good Moral'],
                                ['path' => $student->transfer_credential_path, 'name' => 'Transfer Credential'],
                                ['path' => $student->medical_record_path, 'name' => 'Medical Record'],
                                ['path' => $student->id_picture_path, 'name' => 'ID Picture'],
                                ['path' => $student->enrollment_form_path, 'name' => 'Enrollment Form'],
                            ];
                            $uploadedDocs = collect($sf1Docs)->whereNotNull('path')->pluck('name')->implode(', ');
                        @endphp
                        
                        <div class="animate-fade-in">

                            <!-- ========== SECTION 1: ACADEMIC INFORMATION ========== -->
                            <div>
                                <div class="form-section-header">Academic Information</div>
                                <div class="form-section-body space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                       
                                        <div>
                                            <label class="form-label">Pupil Type</label>
                                            <input type="text" value="{{ ucfirst($pupilType) }}" readonly class="form-input bg-slate-100 text-slate-600 font-semibold">
                                        </div>
                                        <div>
                                            <label class="form-label">Status</label>
                                            <input type="text" value="{{ ucfirst($status) }}" readonly class="form-input bg-slate-100 text-slate-600 font-semibold">
                                        </div>
                                    </div>

                                  

                                    <!-- Checkboxes row -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-dashed border-slate-300">
                                        <div class="flex items-center gap-4">
                                            <span class="text-sm font-semibold text-slate-700">1. With LRN?</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" class="custom-radio" {{ $student->has_lrn ? 'checked' : '' }} disabled>
                                                <span class="text-sm text-slate-600">Yes</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" class="custom-radio" {{ !$student->has_lrn ? 'checked' : '' }} disabled>
                                                <span class="text-sm text-slate-600">No</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-sm font-semibold text-slate-700">2. Returning (Balik-Aral)?</span>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" class="custom-radio" {{ $student->is_returning_balik_aral ? 'checked' : '' }} disabled>
                                                <span class="text-sm text-slate-600">Yes</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" class="custom-radio" {{ !$student->is_returning_balik_aral ? 'checked' : '' }} disabled>
                                                <span class="text-sm text-slate-600">No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ========== SECTION 2: LEARNER INFORMATION ========== -->
                            <div>
                                <div class="form-section-header">Learner Information</div>
                                <div class="form-section-body space-y-4">
                                    
                                    <!-- PSA Birth Cert & LRN row -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="form-label">PSA Birth Certificate No.</label>
                                            <input type="text" value="{{ $student->psa_birth_cert_no ?? 'N/A' }}" readonly class="form-input bg-slate-100 text-slate-600">
                                        </div>
                                       
                                    </div>

                                   

                                    <!-- IP Community -->
                                    <div class="sub-section">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-1">
                                                <label class="form-label">Belonging to any Indigenous Peoples (IP) Community?</label>
                                                <div class="flex gap-4 mt-1">
                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                        <input type="radio" class="custom-radio" {{ $student->is_ip ? 'checked' : '' }} disabled>
                                                        <span class="text-sm text-slate-600">Yes</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                        <input type="radio" class="custom-radio" {{ !$student->is_ip ? 'checked' : '' }} disabled>
                                                        <span class="text-sm text-slate-600">No</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <label class="form-label">If Yes, please specify:</label>
                                                <input type="text" value="{{ $student->ip_specification ?? 'N/A' }}" readonly class="form-input bg-slate-100 text-slate-600">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 4Ps -->
                                    <div class="sub-section">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-1">
                                                <label class="form-label">Is your family a beneficiary of 4Ps?</label>
                                                <div class="flex gap-4 mt-1">
                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                        <input type="radio" class="custom-radio" {{ $student->is_4ps_beneficiary ? 'checked' : '' }} disabled>
                                                        <span class="text-sm text-slate-600">Yes</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                        <input type="radio" class="custom-radio" {{ !$student->is_4ps_beneficiary ? 'checked' : '' }} disabled>
                                                        <span class="text-sm text-slate-600">No</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <label class="form-label">4Ps Household ID Number:</label>
                                                <input type="text" value="{{ $student->household_id_4ps ?? 'N/A' }}" readonly class="form-input bg-slate-100 text-slate-600 font-mono tracking-wider">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                           

                            <!-- ========== SECTION 6: FOR RETURNING/TRANSFEREE ========== -->
                             @if($student->is_returning_balik_aral || $pupilType === 'transferee')
                            <div>
                                <div class="form-section-header">
                                    For Returning Learner (Balik-Aral) and Those Who will Transfer/Move In
                                    <span class="ml-2 px-2 py-0.5 bg-orange-500 text-white text-xs rounded-full">{{ $pupilType === 'transferee' ? 'Transferee' : 'Balik-Aral' }}</span>
                                </div>
                                <div class="form-section-body space-y-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="form-label">Last Grade Level Completed</label>
                                            <input type="text" value="{{ $student->last_grade_level_completed ?? 'N/A' }}" readonly class="form-input bg-slate-100 text-slate-600">
                                        </div>
                                        <div>
                                            <label class="form-label">Last School Year Completed</label>
                                            <input type="text" value="{{ $student->last_school_year_completed ?? 'N/A' }}" readonly class="form-input bg-slate-100 text-slate-600">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="form-label">Last School Attended</label>
                                            <input type="text" value="{{ $student->previous_school ?? 'N/A' }}" readonly class="form-input bg-slate-100 text-slate-600">
                                        </div>
                                        <div>
                                            <label class="form-label">School ID</label>
                                            <input type="text" value="{{ $student->previous_school_id ?? 'N/A' }}" readonly class="form-input bg-slate-100 text-slate-600 font-mono">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- ========== SECTION 7: REMARKS ========== -->
                            <div>
                                <div class="form-section-header">Remarks</div>
                                <div class="form-section-body">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="form-label">Remark Code</label>
                                            <input type="text" value="{{ $student->remarks ? match($student->remarks) {
                                                'TI' => 'TI - Transferred In',
                                                'TO' => 'TO - Transferred Out',
                                                'DO' => 'DO - Dropped Out',
                                                'LE' => 'LE - Late Enrollee',
                                                'CCT' => 'CCT - CCT Recipient',
                                                'BA' => 'BA - Balik Aral',
                                                'LWD' => 'LWD - Learner With Disability',
                                                default => $student->remarks
                                            } : 'None' }}" readonly class="form-input bg-slate-100 text-slate-600">
                                        </div>
                                    </div>
                                </div>
                            </div>

                                                <!-- ========== SF1 TABLE SECTION (RETAINED) ========== -->
                            <div>
                                <div class="form-section-header sf1-blue">School Form 1 (SF1) School Register
                                    <span style="display: block; font-size: 0.75rem; font-weight: 500; color: #94a3b8; text-transform: none; letter-spacing: normal; margin-top: 2px;">(This replaces Form 1, Master List & STS Form 2-Family Background and Profile)</span>
                                </div>
                                    <div class="form-section-body">
                                    <table class="sf1-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 3%;">NO.</th>
                                                <th style="width: 8%;">LRN</th>
                                                <th style="width: 10%;">NAME</th>
                                                <th style="width: 6%;">SCHOOL YEAR</th>
                                                <th style="width: 5%;">STATUS</th>
                                                <th style="width: 8%;">GRADE & SECTION</th>
                                                <th style="width: 6%;">ENROLLED SINCE</th>
                                                <th style="width: 3%;">SEX</th>
                                                <th style="width: 5%;">BIRTH DATE</th>
                                                <th style="width: 3%;">AGE</th>
                                                <th style="width: 5%;">MOTHER TONGUE</th>
                                                <th style="width: 4%;">IP</th>
                                                <th style="width: 4%;">RELIGION</th>
                                                <th style="width: 10%;">ADDRESS</th>
                                                <th style="width: 7%;">FATHER'S NAME</th>
                                                <th style="width: 7%;">MOTHER'S NAME</th>
                                                <th style="width: 7%;">GUARDIAN'S NAME</th>
                                                <th style="width: 4%;">RELATIONSHIP</th>
                                                <th style="width: 4%;">CONTACT</th>
                                                <th style="width: 4%;">REMARKS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="font-mono text-[8px]">{{ $student->lrn ?? '' }}</td>
                                                <td class="text-left uppercase text-[8px]">{{ $fullName }}</td>
                                                <td class="text-center text-[8px]">{{ $schoolYearName }}</td>
                                                <td class="text-center text-[8px] capitalize">{{ $status }}</td>
                                                <td class="text-center text-[8px]">{{ $gradeSection }}</td>
                                                <td class="text-center text-[8px]">{{ $enrolledSince }}</td>
                                                <td class="text-center uppercase text-[8px] font-bold">{{ $sexCode }}</td>
                                                <td class="text-center text-[8px]">{{ $birthDate }}</td>
                                                <td class="text-center text-[8px] font-bold">{{ $age }}</td>
                                                <td class="text-center uppercase text-[8px]">{{ $student->mother_tongue ?? '' }}</td>
                                                <td class="text-center uppercase text-[8px]">{{ $student->ethnicity ?? '' }}</td>
                                                <td class="text-center text-[8px]">{{ $student->religion ?? '' }}</td>
                                                <td class="text-left uppercase text-[7px]">{{ $address }}</td>
                                                <td class="text-left uppercase text-[7px]">{{ $student->father_name ?? '' }}</td>
                                                <td class="text-left uppercase text-[7px]">{{ $student->mother_name ?? '' }}</td>
                                                <td class="text-left uppercase text-[7px]">{{ $student->guardian_name ?? '' }}</td>
                                                <td class="text-center uppercase text-[7px]">{{ $student->guardian_relationship ?? '' }}</td>
                                                <td class="text-center text-[7px]">{{ $student->guardian_contact ?? '' }}</td>
                                                <td class="text-center text-[8px] font-semibold">{{ $student->remarks ?? '' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ========== DOCUMENTS & ATTACHMENTS (RETAINED) ========== -->
                            <div class="glass-card p-6 mt-6">
                                <div class="section-header mb-4">
                                    <i class="fas fa-folder-open" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb;"></i>
                                    <h3>Documents & Attachments</h3>
                                </div>
                                
                                @php
                                    $documents = [
                                        ['path' => $student->birth_certificate_path, 'name' => 'Birth Certificate', 'icon' => 'fa-file-alt', 'color' => 'blue'],
                                        ['path' => $student->report_card_path, 'name' => 'Report Card', 'icon' => 'fa-graduation-cap', 'color' => 'emerald'],
                                        ['path' => $student->good_moral_path, 'name' => 'Good Moral Certificate', 'icon' => 'fa-certificate', 'color' => 'amber'],
                                        ['path' => $student->medical_record_path, 'name' => 'Medical Record', 'icon' => 'fa-file-medical', 'color' => 'rose'],
                                        ['path' => $student->id_picture_path, 'name' => 'ID Picture', 'icon' => 'fa-image', 'color' => 'purple'],
                                        ['path' => $student->enrollment_form_path, 'name' => 'Enrollment Form', 'icon' => 'fa-file-signature', 'color' => 'cyan']
                                    ];
                                @endphp

                                <div class="documents-grid">
                                    @foreach($documents as $doc)
                                        <div class="document-card">
                                            <div class="flex items-center gap-4">
                                                <div class="document-icon {{ $doc['color'] }}">
                                                    <i class="fas {{ $doc['icon'] }}"></i>
                                                </div>
                                                <div class="document-info">
                                                    <h4>{{ $doc['name'] }}</h4>
                                                    <p>{{ $doc['path'] ? 'Document uploaded' : 'No document uploaded' }}</p>
                                                </div>
                                            </div>
                                            @if($doc['path'])
                                                @php
                                                    $docType = match($doc['name']) {
                                                        'Birth Certificate' => 'birth_certificate',
                                                        'Report Card' => 'report_card',
                                                        'Good Moral Certificate' => 'good_moral',
                                                        'Medical Record' => 'medical_record',
                                                        'ID Picture' => 'id_picture',
                                                        'Enrollment Form' => 'enrollment_form',
                                                        default => ''
                                                    };
                                                    $fileExt = pathinfo($doc['path'], PATHINFO_EXTENSION);
                                                @endphp
                                                <button onclick="openDocumentModal('{{ route('admin.students.document.view', ['student' => $student->id, 'type' => $docType]) }}', {{ json_encode($doc['name']) }}, '{{ strtolower($fileExt) }}')" 
                                                        class="btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            @else
                                                <span class="document-status missing">Missing</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Enhanced Footer Info -->
                    <div class="footer-info animate-fade-in">
                        <div class="footer-meta">
                            <div class="footer-meta-item">
                                <i class="fas fa-clock"></i>
                                <span>Created: {{ $student->created_at ? $student->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                            </div>
                            @if($student->updated_at && $student->updated_at != $student->created_at)
                            <div class="footer-meta-item">
                                <i class="fas fa-sync"></i>
                                <span>Updated: {{ $student->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="footer-status">
                            <span>Active Record</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="fab-container" x-data="{ idCardOpen: false }">
        <a href="{{ route('admin.students.edit', $student->id) }}" class="fab-btn edit">
            <i class="fas fa-edit"></i>
            <span class="fab-tooltip">Edit Student</span>
        </a>

        <button @click="idCardOpen = true" class="fab-btn" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
            <i class="fas fa-id-card"></i>
            <span class="fab-tooltip">View ID Card</span>
        </button>

        <!-- ID Card Modal (teleported to body) -->
        <template x-teleport="body">
            <div x-show="idCardOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[9999]"
                 style="display: none;"
                 @keydown.escape.window="idCardOpen = false">
                <div class="relative flex min-h-screen items-center justify-center p-4">
                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="idCardOpen = false"></div>
                    <div x-show="idCardOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                         class="relative w-full max-w-xl rounded-2xl bg-white shadow-2xl p-5"
                         style="display: none;"
                         @click.away="idCardOpen = false">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-slate-800">Student ID Card</h3>
                            <div class="flex items-center gap-2">
                                <button onclick="window.print()" class="inline-flex h-8 px-3 items-center justify-center rounded-full bg-blue-900 text-white hover:bg-blue-800 transition text-xs font-medium">
                                    <i class="fas fa-print mr-1"></i> Print
                                </button>
                                <button @click="idCardOpen = false" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                                    <i class="fas fa-times text-base"></i>
                                </button>
                            </div>
                        </div>
                        @include('components.student-id-card', ['student' => $student, 'showPrint' => false])
                    </div>
                </div>
            </div>
        </template>
        
        <a href="{{ route('admin.students.index') }}" class="fab-btn back">
            <i class="fas fa-arrow-left"></i>
            <span class="fab-tooltip">Back to List</span>
        </a>
    </div>

    <script>
        // Toast auto-hide
        @if(session('success'))
        setTimeout(() => {
            document.getElementById('successToast')?.classList.remove('show');
        }, 4000);
        @endif

        // Tab switching
        function switchTab(tabId, btnElement) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            document.getElementById(tabId).classList.add('active');
            btnElement.classList.add('active');
        }

        // Confirm delete
        function confirmDelete() {
            if(confirm('âš ï¸ Are you sure you want to delete this student?\n\nThis action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if(e.altKey && e.key === 'e') {
                e.preventDefault();
                window.location.href = "{{ route('admin.students.edit', $student->id) }}";
            }
            if(e.altKey && e.key === 'b') {
                e.preventDefault();
                window.location.href = "{{ route('admin.students.index') }}";
            }
            if(e.altKey && e.key === 'd') {
                e.preventDefault();
                confirmDelete();
            }
            if(e.altKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            // Close modal with Escape key
            if(e.key === 'Escape') {
                closeDocumentModal();
            }
        });

        // Document Modal Functions
        function openDocumentModal(url, title, fileType) {
            const modal = document.getElementById('documentModal');
            const backdrop = document.getElementById('documentModalBackdrop');
            const content = document.getElementById('documentModalContent');
            const modalTitle = document.getElementById('documentModalTitle');
            
            modalTitle.textContent = title;
            
            // Add cache buster to prevent browser caching
            const urlWithCache = url + (url.includes('?') ? '&' : '?') + 't=' + Date.now();
            
            let contentHtml = '';
            const fileTypeLower = fileType.toLowerCase();
            
            if (fileTypeLower === 'pdf') {
                contentHtml = `<iframe src="${urlWithCache}" class="w-full" style="border: none; height: 75vh;" type="application/pdf"></iframe>`;
            } else if (['jpg', 'jpeg', 'png'].includes(fileTypeLower)) {
                contentHtml = `<div class="flex justify-center p-4" style="min-height: 400px;"><img id="docPreviewImg" src="${urlWithCache}" alt="${title}" style="max-width: 100%; height: auto; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);" onerror="this.style.display='none'; document.getElementById('docPreviewError').style.display='flex';"><div id="docPreviewError" style="display:none; flex-direction:column; align-items:center; justify-content:center; min-height:400px;"><div style="width:80px; height:80px; background:linear-gradient(135deg,#fef3c7,#fde68a); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-bottom:20px;"><i class="fas fa-exclamation-triangle" style="font-size:32px; color:#d97706;"></i></div><h4 style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:10px;">Document Preview Unavailable</h4><p style="color:#64748b; text-align:center; margin-bottom:20px;">The file could not be loaded. It may be missing or corrupted.</p><a href="${urlWithCache}" download class="btn-primary"><i class="fas fa-download"></i> Download File</a></div></div>`;
            } else {
                contentHtml = `
                    <div class="flex flex-col items-center justify-center p-8" style="min-height: 400px;">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 32px; color: #d97706;"></i>
                        </div>
                        <h4 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">Unsupported File Type</h4>
                        <p style="color: #64748b; text-align: center; margin-bottom: 20px;">This file type cannot be previewed. Please download the file to view it.</p>
                        <a href="${urlWithCache}" download class="btn-primary">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                `;
            }
            
            content.innerHTML = contentHtml;
            
            // Update download button
            const downloadBtn = document.getElementById('documentModalDownload');
            if (downloadBtn) {
                downloadBtn.href = urlWithCache;
            }
            
            modal.style.display = 'block';
            backdrop.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeDocumentModal() {
            const modal = document.getElementById('documentModal');
            const backdrop = document.getElementById('documentModalBackdrop');
            const content = document.getElementById('documentModalContent');
            
            modal.style.display = 'none';
            backdrop.style.display = 'none';
            content.innerHTML = '';
            document.body.style.overflow = '';
        }
    </script>

    <!-- Document Viewer Modal -->
    <div id="documentModalBackdrop" class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm z-50" style="display: none;" onclick="closeDocumentModal()"></div>
    <div id="documentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col" style="max-height: 90vh;">
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b flex-shrink-0" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                <div class="flex items-center gap-3">
                    <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-alt" style="color: white; font-size: 20px;"></i>
                    </div>
                    <div>
                        <h3 id="documentModalTitle" class="font-bold text-slate-900" style="font-size: 18px;">Document</h3>
                        <p class="text-slate-500" style="font-size: 13px;">Document Viewer</p>
                    </div>
                </div>
                <button onclick="closeDocumentModal()" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:border-slate-300 flex items-center justify-center transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Content -->
            <div id="documentModalContent" class="bg-slate-50 flex-1" style="overflow-y: auto; min-height: 0;">
                <!-- Content will be injected here -->
            </div>
            
            <!-- Footer -->
            <div class="flex items-center justify-between p-4 border-t bg-slate-50 flex-shrink-0">
                <div class="text-slate-500" style="font-size: 13px;">
                    <i class="fas fa-info-circle mr-1"></i>
                    Use browser zoom (Ctrl +/-) to resize
                </div>
                <div class="flex gap-2">
                    <button onclick="closeDocumentModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-all font-medium" style="font-size: 14px;">
                        Close
                    </button>
                    <a id="documentModalDownload" href="#" download class="btn-primary" style="font-size: 14px;">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
