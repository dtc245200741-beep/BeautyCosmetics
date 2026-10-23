\# BeautyCosmetics — WordPress Monitoring



\## 1. Giới thiệu dự án



\*\*BeautyCosmetics\*\* là dự án website quảng bá mỹ phẩm được xây dựng trên WordPress, triển khai bằng Docker Compose và tích hợp hệ thống giám sát, thu thập log và cảnh báo.



Dự án giúp theo dõi tình trạng hoạt động của website và các dịch vụ chạy trong container.



\## 2. Công nghệ sử dụng



\- WordPress và PHP

\- MySQL

\- Docker và Docker Compose

\- Nginx và HTTPS

\- Prometheus

\- Grafana

\- Node Exporter, cAdvisor, MySQL Exporter và Blackbox Exporter

\- Loki và Promtail

\- Alertmanager

\- phpMyAdmin



\## 3. Kiến trúc hệ thống



\- \*\*Website:\*\* WordPress cung cấp nội dung website.

\- \*\*Reverse Proxy:\*\* Nginx tiếp nhận yêu cầu HTTP/HTTPS và chuyển tiếp đến WordPress.

\- \*\*Database:\*\* MySQL lưu trữ dữ liệu website.

\- \*\*Monitoring:\*\* Prometheus thu thập metrics từ các target đã cấu hình.

\- \*\*Dashboard:\*\* Grafana hiển thị dữ liệu giám sát.

\- \*\*Logging:\*\* Promtail thu thập log và gửi đến Loki.

\- \*\*Alerting:\*\* Prometheus đánh giá các luật cảnh báo và gửi cảnh báo đến Alertmanager.



\## 4. Cấu trúc thư mục



```text

de3-wordpress-products/

├── custom-css/

├── docs/

├── grafana/

├── loki/

├── mysql/

│   └── init/

├── nginx/

│   ├── certs/

│   ├── conf.d/

│   └── nginx.conf

├── prometheus/

├── promtail/

├── beauty-products.php

├── docker-compose.yml

├── .gitignore

└── README.md

```



\## 5. Yêu cầu môi trường



\- Docker Desktop đã được cài đặt và đang chạy.

\- Docker Compose hoạt động bình thường.

\- Git được cài đặt nếu cần lấy mã nguồn từ GitHub.

\- Các cổng cần sử dụng không bị ứng dụng khác chiếm.

\- Tạo file `.env` và cấu hình các biến môi trường theo `docker-compose.yml`.



\*\*Lưu ý:\*\* Không đưa file `.env`, mật khẩu, khóa riêng hoặc thông tin xác thực lên GitHub.



\## 6. Khởi chạy dự án



Mở PowerShell và chuyển đến thư mục dự án:



```powershell

cd D:\\de3-wordpress-products

```



Kiểm tra cấu hình Docker Compose:



```powershell

docker compose config

```



Khởi chạy các dịch vụ:



```powershell

docker compose up -d

```



Kiểm tra trạng thái container:



```powershell

docker compose ps

```



Xem log khi cần xử lý sự cố:



```powershell

docker compose logs --tail=100

```



\## 7. Địa chỉ truy cập



| Thành phần | Địa chỉ |

|---|---|

| Website HTTP | http://localhost:8088 |

| Website HTTPS | https://localhost:8443 |

| Grafana | http://localhost:3001 |

| Prometheus | http://localhost:9091 |

| Alertmanager | http://localhost:9094 |



HTTPS sử dụng chứng chỉ localhost của môi trường phát triển. Trình duyệt có thể cảnh báo chứng chỉ tự ký. Không sử dụng chứng chỉ này cho môi trường production.



Thông tin đăng nhập Grafana được lấy từ cấu hình môi trường của dự án. Không ghi mật khẩu thực tế vào README.



\## 8. Kiểm tra hệ thống giám sát



\### 8.1. Prometheus



Truy cập http://localhost:9091 và mở \*\*Status → Targets\*\* để kiểm tra trạng thái các target.



Các target đã được kiểm tra trong quá trình phát triển gồm:



\- Prometheus

\- Node Exporter

\- cAdvisor

\- MySQL Exporter

\- WordPress HTTP



Trạng thái `UP` cho biết target đang được scrape thành công tại thời điểm kiểm tra.



\### 8.2. Grafana



Truy cập http://localhost:3001 và đăng nhập bằng tài khoản đã cấu hình.



Sử dụng dashboard để theo dõi các chỉ số của hệ thống.



\### 8.3. Logging với Loki



Trong Grafana, mở \*\*Explore\*\* và chọn datasource Loki.



Có thể thử truy vấn sau nếu nhãn `container` tồn tại trong dữ liệu log:



```logql

{container=\~".+"}

```



\### 8.4. Alertmanager



Truy cập http://localhost:9094 để xem trạng thái cảnh báo.



Các cảnh báo phụ thuộc vào điều kiện được định nghĩa trong cấu hình Prometheus và metrics thực tế.



\## 9. Kiểm tra cấu hình



Kiểm tra cấu hình Nginx:



```powershell

docker compose exec nginx nginx -t

```



Kiểm tra cấu hình Prometheus:



```powershell

docker compose exec prometheus promtool check config /etc/prometheus/prometheus.yml

```



\## 10. Dừng hệ thống



Dừng các container:



```powershell

docker compose stop

```



Dừng và xóa các container thuộc dự án:



```powershell

docker compose down

```



Không thêm tùy chọn `-v` nếu chưa có kế hoạch sao lưu dữ liệu, vì tùy chọn này có thể xóa các volume dữ liệu.



\## 11. Bảo mật và vận hành



\- Không commit file `.env` hoặc thông tin xác thực lên GitHub.

\- Giới hạn quyền truy cập các dịch vụ quản trị.

\- Không công khai trực tiếp cổng MySQL ra Internet.

\- Kiểm tra log và trạng thái target khi có sự cố.

\- Sao lưu dữ liệu trước khi thay đổi cấu hình hoặc nâng cấp dịch vụ.

\- Kiểm tra chứng chỉ TLS trước khi triển khai production.



\## 12. Repository



GitHub: https://github.com/dtc245200741-beep/BeautyCosmetics

