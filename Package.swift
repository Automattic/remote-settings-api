// swift-tools-version: 6.0
// The swift-tools-version declares the minimum version of Swift required to build this package.

import PackageDescription

let package = Package(
    name: "wordpress-settings",
    platforms: [
        .iOS(.v18),
        .macOS(.v14),
    ],
    products: [
        // Products define the executables and libraries a package produces, making them visible to other packages.
        .library(
            name: "WordPressSettings",
            targets: ["WordPressSettings"]),
    ],
    targets: [
        // Targets are the basic building blocks of a package, defining a module or a test suite.
        // Targets can depend on other targets in this package and products from dependencies.
        .target(
            name: "WordPressSettings"),
        .testTarget(
            name: "WordPressSettingsTests",
            dependencies: ["WordPressSettings"],
            path: "Sources/WordPressSettingsTests",
            resources: [.copy("Resources")]
        ),
    ]
)
