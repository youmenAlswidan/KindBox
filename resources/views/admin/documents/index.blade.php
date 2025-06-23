<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مراجعة وثائق المتاجر</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #f2f0ff, #ffffff);
            color: #333;
        }

        header {
            background-color: #9775FA;
            padding: 1.5rem;
            text-align: center;
            color: white;
            position: relative;
        }

        header::before {
            content: '';
            background: url('https://cdn-icons-png.flaticon.com/512/5957/5957034.png') no-repeat center;
            background-size: contain;
            position: absolute;
            top: 10px;
            left: 10px;
            width: 50px;
            height: 50px;
        }

        h1 {
            font-size: 2.5rem;
        }

        .message {
            text-align: center;
            padding: 1rem;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            background-color: #FFFFFF;
            padding: 1.5rem 0;
            border-bottom: 2px solid #C3C3C3;
        }

        .stat {
            background-color: #DE959C;
            color: white;
            padding: 1rem;
            border-radius: 16px;
            width: 28%;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            transition: transform 0.3s;
        }

        .stat:hover {
            transform: translateY(-5px);
        }

        .stat img {
            width: 30px;
            height: 30px;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .stat h3 {
            margin-bottom: 0.5rem;
            font-size: 1.4rem;
        }

        .stat p {
            font-size: 2rem;
            margin-top: 0.5rem;
        }

        .content {
            padding: 2rem;
            background-color: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #9775FA;
            color: white;
            padding: 1rem;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        a {
            color: #9775FA;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-approve {
            background-color: #9775FA;
            color: white;
        }

        .btn-approve:hover {
            background-color: #7B5DE3;
        }

        .btn-reject {
            background-color: #DE959C;
            color: white;
        }

        .btn-reject:hover {
            background-color: #C7757D;
        }

        .status {
            font-weight: bold;
        }

        .status.pending {
            color: orange;
        }

        .status.approved {
            color: green;
        }

        .status.rejected {
            color: red;
        }

        #documentModal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            direction: ltr;
        }

        #documentModal .modal-content {
            position: relative;
            background: #fff;
            padding: 1rem;
            width: 85%;
            max-width: 1000px;
            height: 80vh;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
        }

        #closeModal {
            position: absolute;
            top: 10px; right: 10px;
            background: #DE959C;
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        #closeModal:hover {
            background: #C7757D;
        }

        #pdfViewer {
            flex-grow: 1;
            border: none;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>

    <header>
        <h1>مراجعة وثائق المتاجر</h1>
    </header>

    @if(session('success'))
        <div class="message success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif

    <div class="stats">
        <div class="stat" style="background-color: #9775FA;">
            <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="approved">
            <h3>مقبولة</h3>
            <p>{{ $counts['approved'] }}</p>
        </div>
        <div class="stat" style="background-color: #DE959C;">
            <img src="https://cdn-icons-png.flaticon.com/512/1828/1828665.png" alt="rejected">
            <h3>مرفوضة</h3>
            <p>{{ $counts['rejected'] }}</p>
        </div>
        <div class="stat" style="background-color: #C3C3C3; color: #000;">
            <img src="https://cdn-icons-png.flaticon.com/512/5957/5957092.png" alt="pending">
            <h3>قيد المراجعة</h3>
            <p>{{ $counts['pending'] }}</p>
        </div>
    </div>

    <div class="content">
        @if($documents->isEmpty())
            <p style="text-align: center;">لا توجد وثائق قيد المراجعة حالياً.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>اسم المستخدم</th>
                        <th>اسم المتجر</th>
                        <th>نوع الوثيقة</th>
                        <th>الوثيقة</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                        <tr>
                            <td>{{ $document->shop->user->first_name }} {{ $document->shop->user->last_name }}</td>
                            <td>{{ $document->shop->shop_name }}</td>
                            <td>{{ $document->document_type }}</td>
                            <td>
                                <a href="#" class="show-document" data-id="{{ $document->id }}">عرض</a>
                            </td>
                            <td class="status {{ $document->status }}">{{ $document->status }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.documents.approve', $document->id) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-approve" type="submit">موافقة</button>
                                </form>
                                <form method="POST" action="{{ route('admin.documents.reject', $document->id) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-reject" type="submit">رفض</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal -->
    <div id="documentModal">
        <div class="modal-content">
            <button id="closeModal">إغلاق</button>
            <iframe id="pdfViewer" src=""></iframe>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('documentModal');
            const closeModalBtn = document.getElementById('closeModal');
            const pdfViewer = document.getElementById('pdfViewer');

            document.querySelectorAll('.show-document').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const documentId = this.dataset.id;

                    fetch(`/admin/documents/file/${documentId}`)
                        .then(response => response.json())
                        .then(data => {
                            if(data.file_url) {
                                pdfViewer.src = data.file_url;
                                modal.style.display = 'flex';
                            } else {
                                alert('تعذر تحميل الوثيقة.');
                            }
                        })
                        .catch(() => alert('حدث خطأ أثناء تحميل الوثيقة.'));
                });
            });

            closeModalBtn.addEventListener('click', () => {
                modal.style.display = 'none';
                pdfViewer.src = '';
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    pdfViewer.src = '';
                }
            });
        });
    </script>

</body>
</html>
